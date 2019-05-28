<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication;

use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Authentication\Exception\NotAuthenticated;
use Circli\Modules\Auth\Authentication\Provider\ProviderFactory;
use Circli\Modules\Auth\Authentication\Provider\ProviderFactoryInterface;
use Circli\Modules\Auth\Events\BeforeLogin;
use Circli\Modules\Auth\Events\LoginError;
use Circli\Modules\Auth\Events\LoginSuccess;
use Circli\Modules\Auth\Events\PostLogin;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Repositories\LoginLogRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Handler
{
    /** @var LoginLogRepositoryInterface */
    private $loginLogRepository;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var AccountTokenRepositoryInterface */
    private $accountTokenRepository;

    /**
     * Factory to get the login provider
     *
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @param LoginLogRepositoryInterface $logger
     * @param ProviderFactoryInterface $providerFactory
     * @param EventDispatcherInterface $eventDispatcher
     * @param AccountTokenRepositoryInterface $accountTokenRepository
     */
    public function __construct(
        LoginLogRepositoryInterface $logger,
        ProviderFactoryInterface $providerFactory,
        EventDispatcherInterface $eventDispatcher,
        AccountTokenRepositoryInterface $accountTokenRepository
    ) {
        $this->loginLogRepository = $logger;
        $this->providerFactory = $providerFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->accountTokenRepository = $accountTokenRepository;
    }

    /**
     * @param  LoginDataInterface $info
     * @return AuthResponse
     */
    public function handle(LoginDataInterface $info): AuthResponse
    {
        $provider = $this->providerFactory->resolveProvider($info->getProvider());

        $event = new BeforeLogin($info);
        $this->eventDispatcher->dispatch($event);

        if ($event->getAuthResponseCode()) {
            $this->loginLogRepository->addLoginAttempt(0, $info, 'event-fail');
            return new AuthResponse($event->getAuthResponseCode());
        }

        try {
            $account = $provider->verify($info);

            $sessionToken = $this->accountTokenRepository->create(
                $account,
                TokenProviders::SESSION,
                new \DateTimeImmutable('+30 min')
            );

            $authResponse = new AuthResponse(AuthResponseStatus::SUCCESS(), $account, $sessionToken);
            $this->eventDispatcher->dispatch(new LoginSuccess($account, $info));

            $this->loginLogRepository->addLoginAttempt($account->getId(), $info, 'success');
        } catch (NotAuthenticated $nae) {
            $this->loginLogRepository->addLoginAttempt($nae->getAccountId(), $info, 'fail');
            $this->eventDispatcher->dispatch(new LoginError($nae));
            $authResponse = new AuthResponse(AuthResponseStatus::FAIL());
        } catch (AccountNotFound $nfe) {
            $this->loginLogRepository->addLoginAttempt(0, $info, 'not_found');
            $this->eventDispatcher->dispatch(new LoginError($nfe));
            $authResponse = new AuthResponse(AuthResponseStatus::NOT_FOUND());
        }
        $this->eventDispatcher->dispatch(new PostLogin($authResponse, $info));

        return $authResponse;
    }
}
