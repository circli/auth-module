<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Provider;

use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Authentication\Data\TokenLoginDataInterface;
use Circli\Modules\Auth\Authentication\Exception\NotAuthenticated;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;

/**
 * Handles all oauth token authentications
 */
final class Token implements ProviderInterface
{
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var AccountTokenInterface|null */
    private $currentToken;

    /**
     * @param AccountTokenRepositoryInterface $tokenRepository
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(
        AccountTokenRepositoryInterface $tokenRepository,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param LoginDataInterface|TokenLoginDataInterface $info
     * @return AccountInterface
     */
    public function verify(LoginDataInterface $info): AccountInterface
    {
        if (!$info instanceof TokenLoginDataInterface) {
            throw new AccountNotFound();
        }

        $tokens = $this->tokenRepository->findByProviderAndUid($info->getTokenProvider(), $info->getTokenUid());

        if (!count($tokens)) {
            throw new AccountNotFound();
        }

        $error = null;

        $accountId = 0;

        foreach ($tokens as $token) {
            $currentError = null;
            if ($token->haveExpired(new \DateTimeImmutable())) {
                $currentError = $error = new NotAuthenticated('Token has expired', $token->getAccountId(), NotAuthenticated::EXPIRED);
            }
            if (!$currentError && $token->isValid($info->getToken())) {
                $this->currentToken = $token;
                return $this->accountRepository->findByToken($token);
            }
            $accountId = $token->getAccountId();
        }

        if ($error) {
            throw $error;
        }

        throw new NotAuthenticated('Invalid ' . $info->getTokenProvider() . ' token', $accountId, NotAuthenticated::INVALID);
    }

    public function getCurrentToken(): ?AccountTokenInterface
    {
        return $this->currentToken;
    }
}
