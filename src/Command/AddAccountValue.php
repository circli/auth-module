<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Command;

use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\Objects\Value;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class AddAccountValue extends Command
{
    protected static $defaultName = 'circli:auth:account:value:add';
    /** @var AccountRepositoryInterface */
    private $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        parent::__construct();
        $this->accountRepository = $accountRepository;
    }

    protected function configure()
    {
        $this
            ->addArgument('account_id', InputArgument::REQUIRED, 'Account id')
            ->addArgument('key', InputArgument::REQUIRED, 'Value key')
            ->addArgument('value', InputArgument::REQUIRED, 'Value to store')
            ->addOption('encrypted', 'e', InputOption::VALUE_NONE, 'Encrypt value this is the default')
            ->addOption('raw', 'u', InputOption::VALUE_NONE, 'Don\'t encrypt value');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $id = (int)$input->getArgument('account_id');
        $key = $input->getArgument('key');
        $value = $input->getArgument('value');
        $encrypt = $input->getOption('raw') !== true;

        $account = $this->accountRepository->findById($id);

        $valueObject = $encrypt ? Value::newEncryptedValue($key, $value) : Value::newValue($key, $value);

        $this->accountRepository->addAccountValue($account, $valueObject);
    }
}
