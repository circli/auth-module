<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Command;

use Circli\Modules\Auth\Events\AccountCreated;
use Circli\Modules\Auth\Repositories\AccessRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Enums\Status;
use Circli\Modules\Auth\Repositories\Objects\StaticRole;
use Circli\Modules\Auth\Repositories\Objects\Value;
use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\EmailAddress;
use Circli\Modules\Auth\Web\InputData\AuthMethodData;
use Circli\Modules\Auth\Web\InputData\PasswordProviderData;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateAccount extends Command
{
    protected static $defaultName = 'circli:auth:account:create';
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var AccountTokenRepositoryInterface */
    private $accountTokenRepository;
    /** @var AccessRepositoryInterface */
    private $accessRepository;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        AccountTokenRepositoryInterface $accountTokenRepository,
        AccessRepositoryInterface $accessRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct();
        $this->accountRepository = $accountRepository;
        $this->accountTokenRepository = $accountTokenRepository;
        $this->accessRepository = $accessRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addOption('status', 's', InputOption::VALUE_REQUIRED, 'Account status', 'approved')
            ->addOption('roles', 'r', InputOption::VALUE_REQUIRED, 'Role ids in a comma separated list', '1');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $email = EmailAddress::fromEmailAddress($input->getArgument('email'));
        $password = ClearTextPassword::fromInputPassword($input->getArgument('password'));
        $status = Status::fromValue($input->getOption('status') ?? Status::APPROVED);
        $secret = bin2hex(random_bytes(32));
        $inputRoles = explode(',', $input->getOption('roles'));
        $roles = [];
        foreach ($inputRoles as $role) {
            $roles[] = StaticRole::fromId((int) $role);
        }

        $emailIndex = $this->accountTokenRepository->getHashIndex($email);
        $tokens = $this->accountTokenRepository->findByProviderAndUid('password', $emailIndex);
        if ($tokens && count($tokens)) {
            throw new \DomainException('Email already in use');
        }

        //todo add transaction
        $account = $this->accountRepository->create($secret, $status);

        $this->accountTokenRepository->createFromAuthData($account,
            new AuthMethodData('password', new PasswordProviderData($email, $password)));

        foreach ($roles as $role) {
            $this->accessRepository->addAccountRole($account, $role);
        }

        $account = $this->accountRepository->refreshById($account->getId());

        $this->eventDispatcher->dispatch(new AccountCreated($account));
    }
}
