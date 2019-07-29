<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Command;

use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ViewAccount extends Command
{
    protected static $defaultName = 'circli:auth:account';
    /** @var AccountRepositoryInterface */
    private $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        parent::__construct();
        $this->accountRepository = $accountRepository;
    }

    protected function configure()
    {
        $this->addArgument('account_id', InputArgument::REQUIRED, 'Account id');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $id = (int)$input->getArgument('account_id');

        $account = $this->accountRepository->findById($id);

        $output->writeln('Account #' . $account->getId());
        $output->writeln('Status: ' . $account->getStatus()->getValue());
        $output->writeln('Values');
        foreach ($account->getValues() as $value) {
            $output->write("\t" . $value->getKey());
            $output->writeln(': ' . $value->getValue());
        }
        $output->writeln("\n\n");
    }
}
