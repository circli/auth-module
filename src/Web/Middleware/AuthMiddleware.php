<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Middleware;

use Circli\Middlewares\RequestAttributeKeys as CoreRequestAttributeKeys;
use Circli\Modules\Auth\Auth;
use Circli\Modules\Auth\Authentication\Data\TokenLoginData;
use Circli\Modules\Auth\Authentication\Exception\NotAuthenticated;
use Circli\Modules\Auth\Authentication\Provider\Token as TokenProvider;
use Circli\Modules\Auth\Authentication\TokenProviders;
use Circli\Modules\Auth\Events\PostAuthenticate;
use Circli\Modules\Auth\Repositories\AccessRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\GuestAccount;
use Circli\Modules\Auth\Session\Factory as SessionFactory;
use Circli\Modules\Auth\Voter\AccessCheckers;
use Circli\Modules\Auth\Web\RequestAttributeKeys;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /** @var AccessRepositoryInterface */
    private $accessRepository;
    /** @var LoggerInterface */
    private $logger;
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var TokenProvider */
    private $tokenProvider;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var AccessCheckers */
    private $accessCheckers;
    /** @var AccountInterface|GuestAccount */
    private $guestAccount;

    public function __construct(
        TokenProvider $tokenProvider,
        AccountTokenRepositoryInterface $tokenRepository,
        AccessRepositoryInterface $accessRepository,
        EventDispatcherInterface $eventDispatcher,
        AccessCheckers $accessCheckers,
        LoggerInterface $logger,
        SessionFactory $sessionFactory,
        AccountInterface $guestAccount = null
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenProvider = $tokenProvider;
        $this->guestAccount = $guestAccount ?? new GuestAccount();
        $this->logger = $logger;
        $this->tokenRepository = $tokenRepository;
        $this->sessionFactory = $sessionFactory;
        $this->accessRepository = $accessRepository;
        $this->accessCheckers = $accessCheckers;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authSession = $this->sessionFactory->fromRequest($request);

        $acl = null;
        if ($authSession->getSessionData()) {
            [$uid, $token] = $authSession->getSessionData();
            try {
                $account = $this->tokenProvider->verify(
                    new TokenLoginData(
                        TokenProviders::SESSION,
                        $uid,
                        $token,
                        $request->getAttribute(CoreRequestAttributeKeys::IP, '127.0.0.1'),
                        $request->getAttribute(CoreRequestAttributeKeys::USER_AGENT, '')
                    )
                );

                $account = $this->accessRepository->populateRoles($account);

                $sessionToken = $this->tokenProvider->getCurrentToken();
                if ($sessionToken) {
                    $sessionToken->setExpire(new \DateTimeImmutable('+30 min'));
                    $this->tokenRepository->save($sessionToken);

                    $request = $request->withAttribute(RequestAttributeKeys::SESSION_TOKEN, $sessionToken);
                }
            }
            catch (AccountNotFound $anf) {
                $this->logger->info('Session account not found', [
                    'session' => $authSession,
                ]);
            }
            catch (NotAuthenticated $na) {
                if ($na->getCode() === NotAuthenticated::EXPIRED) {
                    $this->logger->debug('Session token have expired', [
                        'session' => $authSession,
                    ]);
                }
                else {
                    $this->logger->warning('Session token invalid', [
                        'session' => $authSession,
                    ]);
                }
            }
        }

        $auth = new Auth($account ?? $this->guestAccount, $this->eventDispatcher);
        $this->accessCheckers->setAccount($auth->getAccount());
        $this->eventDispatcher->dispatch(new PostAuthenticate($auth, $auth->getAccount()));
        $request = $request->withAttribute(RequestAttributeKeys::AUTH, $auth);
        $request = $request->withAttribute(RequestAttributeKeys::ACCOUNT, $auth->getAccount());

        return $handler->handle($request);
    }
}
