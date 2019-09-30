<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Command;

use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\Objects\ValueInterface;
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

        $this->renderValues($output, $account->getValues());

        $output->writeln("\n\n");
    }

    private function renderValues(OutputInterface $output, iterable $values, int $level = 1): void
    {
        foreach ($values as $key => $value) {
            if ($value instanceof ValueInterface) {
                $key = $value->getKey();
                $value = $value->getValue();
            }
            $output->write(str_repeat("\t", $level));
            $output->write($key . ': ');

            if (is_iterable($value)) {
                $output->write("\n");
                $this->renderValues($output, $value, $level + 1);
            }
            elseif (is_string($value) || is_numeric($value)) {
                $output->writeln($value);
            }
            else {
                $output->writeln(json_encode($value, JSON_PRETTY_PRINT));
            }
        }
    }
}
