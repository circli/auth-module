<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Middleware;

use Circli\Modules\Auth\Events\Providers\RememberMeProvider;
use Circli\Modules\Auth\Value\RawTokenValue;
use Circli\WebCore\Contracts\CookieFactory;
use Circli\WebCore\Session\Factory as SessionFactory;
use Circli\WebCore\Session\Flash\Message;
use Circli\WebCore\Session\FlashSession;
use Circli\Middlewares\RequestAttributeKeys as CoreRequestAttributeKeys;
use Circli\Modules\Auth\Auth;
use Circli\Modules\Auth\Authentication\Data\TokenLoginData;
use Circli\Modules\Auth\Authentication\Exception\NotAuthenticated;
use Circli\Modules\Auth\Authentication\Provider\Token as TokenProvider;
use Circli\Modules\Auth\Authentication\TokenProviders;
use Circli\Modules\Auth\Cookies;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Session\Factory as AuthSessionFactory;
use Circli\Modules\Auth\Web\RequestAttributeKeys;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class RememberMeMiddleware implements MiddlewareInterface
{
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var LoggerInterface */
    private $logger;
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;
    /** @var CookieFactory */
    private $cookieFactory;
    /** @var AuthSessionFactory */
    private $authSessionFactory;
    /** @var TokenProvider */
    private $tokenProvider;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var RememberMeProvider */
    private $rememberMeProvider;
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        TokenProvider $tokenProvider,
        AccountTokenRepositoryInterface $tokenRepository,
        AuthSessionFactory $authSessionFactory,
        SessionFactory $sessionFactory,
        CookieFactory $cookieFactory,
        EventDispatcherInterface $eventDispatcher,
        RememberMeProvider $rememberMeProvider,
        ResponseFactoryInterface $responseFactory,
        LoggerInterface $logger
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenProvider = $tokenProvider;
        $this->logger = $logger;
        $this->tokenRepository = $tokenRepository;
        $this->cookieFactory = $cookieFactory;
        $this->authSessionFactory = $authSessionFactory;
        $this->sessionFactory = $sessionFactory;
        $this->rememberMeProvider = $rememberMeProvider;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Auth $auth */
        $auth = $request->getAttribute(RequestAttributeKeys::AUTH);
        if (!$auth) {
            $this->logger->critical(self::class . ' must be after the ' . AuthMiddleware::class . 'in the middleware order');
            return $handler->handle($request);
        }

        $rememberCookie = $this->cookieFactory->getCookie(Cookies::REMEMBER_ME);
        $this->rememberMeProvider->setCookie($rememberCookie);

        if (!$auth->isAuthenticated() && $rememberCookie->exits() && $rememberCookie->getValue()) {
            $rememberToken = RawTokenValue::fromCookie($rememberCookie);
            /** @var FlashSession $flashSession */
            $flashSession = $this->sessionFactory->fromRequest($request, FlashSession::class);
            try {
                $account = $this->tokenProvider->verify(
                    new TokenLoginData(
                        TokenProviders::REMEMBER_ME,
                        $rememberToken->getUid(),
                        $rememberToken->getToken(),
                        $request->getAttribute(CoreRequestAttributeKeys::IP),
                        $request->getAttribute(CoreRequestAttributeKeys::USER_AGENT, '')
                    )
                );

                $sessionToken = $this->tokenRepository->create(
                    $account,
                    TokenProviders::SESSION,
                    new \DateTimeImmutable('+30 min')
                );
                $authSession = $this->authSessionFactory->fromRequest($request);
                $authSession->persist($sessionToken);

                if ($account) {
                    $auth = new Auth($account, $this->eventDispatcher);
                    $request = $request->withAttribute(RequestAttributeKeys::AUTH, $auth);
                    $request = $request->withAttribute(RequestAttributeKeys::ACCOUNT, $auth->getAccount());

                    return $this->responseFactory
                        ->createResponse(302)
                        ->withHeader('location', (string)$request->getUri());
                }
            }
            catch (AccountNotFound $anf) {
                $this->logger->info("Remember me token don't have account" , [
                    'uid' => $rememberToken->getUid(),
                    'token' => $rememberToken->getToken(),
                ]);
            }
            catch (NotAuthenticated $na) {
                if ($na->getCode() === NotAuthenticated::EXPIRED) {
                    $flashSession->addMessage(Message::info('Remember me token have expired'), 'global');
                    $this->logger->debug('Remember me token have expired', [
                        'uid' => $rememberToken->getUid(),
                        'token' => $rememberToken->getToken(),
                    ]);
                }
                else {
                    $this->logger->warning('Remember me token invalid', [
                        'uid' => $rememberToken->getUid(),
                        'token' => $rememberToken->getToken(),
                    ]);
                }
            }
        }

        return $handler->handle($request);
    }
}
