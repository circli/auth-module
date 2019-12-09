<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Command;

use Circli\Modules\Auth\Authentication\PasswordHasher;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\Hash;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ChangePassword
{
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;
    /** @var PasswordHasher */
    private $passwordHasher;
    /** @var AccountRepositoryInterface */
    private $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository, AccountTokenRepositoryInterface $tokenRepository, PasswordHasher $passwordHasher)
    {
        $this->tokenRepository = $tokenRepository;
        $this->accountRepository = $accountRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $account = $this->accountRepository->findById((int)$input->getArgument('account_id'));

        $password = ClearTextPassword::fromInputPassword($input->getArgument('password'));

        $passwordToken = $this->tokenRepository->findByAccountAndProvider($account, 'password');
        $passwordToken->setToken($password->makeHash($this->passwordHasher));

        $this->tokenRepository->save($passwordToken);
    }
}
