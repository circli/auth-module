<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Command;

use Circli\Modules\Auth\Repositories\AccessRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Objects\Value;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CleanTokens extends Command
{
    protected static $defaultName = 'circli:auth:clean-tokens';
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;

    public function __construct(AccountTokenRepositoryInterface $tokenRepository)
    {
        parent::__construct();
        $this->tokenRepository = $tokenRepository;
    }

    protected function configure()
    {
        $this
            ->addArgument('type', InputArgument::OPTIONAL, 'Type of tokens to cleanup', 'all');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        if ($type === 'all') {
            $tokens = $this->tokenRepository->findExpired();
        }
        else {
            $tokens = $this->tokenRepository->findExpiredByProvider($type);
        }

        $count = 0;
        foreach ($tokens as $token) {
            $count += (int)$this->tokenRepository->delete($token);
        }

        $output->writeln('Deleted ' . $count . ' tokens');
    }
}
