<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Provider;

use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Authentication\Data\PasswordLoginDataInterface;
use Circli\Modules\Auth\Authentication\Exception\NotAuthenticated;
use Circli\Modules\Auth\Authentication\PasswordHasher;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

/**
 * Handle basic password based login
 */
final class Password implements ProviderInterface
{
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var PasswordHasher */
    private $passwordHasher;
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;

    /**
     * @param AccountTokenRepositoryInterface $tokenRepository
     * @param AccountRepositoryInterface $accountRepository
     * @param PasswordHasher $passwordHasher
     */
    public function __construct(
        AccountTokenRepositoryInterface $tokenRepository,
        AccountRepositoryInterface $accountRepository,
        PasswordHasher $passwordHasher
    ) {
        $this->accountRepository = $accountRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param LoginDataInterface|PasswordLoginDataInterface $info
     * @return AccountInterface
     */
    public function verify(LoginDataInterface $info): AccountInterface
    {
        if (!$info instanceof PasswordLoginDataInterface) {
            throw new AccountNotFound();
        }

        $emailIndex = $this->tokenRepository->getHashIndex($info->getEmail());

        $tokens = $this->tokenRepository->findByProviderAndUid('password', $emailIndex);

        if (!count($tokens)) {
            throw new AccountNotFound();
        }

        $accountId = 0;
        foreach ($tokens as $token) {
            if ($info->getPassword()->verify($token->getToken(), $this->passwordHasher)) {
                if ($this->passwordHasher->needsRehash($token->getToken())) {
                    $token->setToken($info->getPassword()->makeHash($this->passwordHasher));
                    $this->tokenRepository->save($token);
                }

                $account = $this->accountRepository->findByToken($token);

                if ($info->rememberMe()) {
                    $this->tokenRepository->create($account, 'remember-me', new \DateTimeImmutable('+2 weeks'));
                }

                return $account;
            }
            $accountId = $token->getAccountId();
        }

        throw new NotAuthenticated('Wrong credentials', $accountId);
    }
}
