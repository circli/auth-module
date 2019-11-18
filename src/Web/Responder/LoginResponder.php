<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Circli\WebCore\Session\Factory as SessionFactory;
use Circli\WebCore\Session\Flash\Message;
use Circli\WebCore\Session\FlashSession;
use Circli\Modules\Auth\Web\Payload\LoginPayload;
use Circli\Modules\Auth\Events\BeforeLoginRedirect;
use Circli\Modules\Auth\Session\Factory as AuthSessionFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class LoginResponder
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var AuthSessionFactory */
    private $authSessionFactory;

    public function __construct(
        AuthSessionFactory $authSessionFactory,
        SessionFactory $sessionFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->authSessionFactory = $authSessionFactory;
        $this->sessionFactory = $sessionFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PayloadInterface $payload
    ): ResponseInterface {
        $response = $response->withStatus(303);

        if ($payload instanceof LoginPayload && $payload->getStatus() === LoginPayload::SUCCESS) {
            $authResponse = $payload->getAuthResponse();
            if ($authResponse) {
                $authSession = $this->authSessionFactory->fromRequest($request);
                $authSession->persist($authResponse->getSessionToken());
            }
        }
        else {
            /** @var FlashSession $flashSession */
            $flashSession = $this->sessionFactory->fromRequest($request, FlashSession::class);
            $flashSession->addMessage(Message::error($payload->getMessages()), 'global');
        }

        if ($payload instanceof LoginPayload) {
            $loginRedirectEvent = BeforeLoginRedirect::fromAuthResponse($request, $payload->getAuthResponse());
        }
        else {
            $loginRedirectEvent = BeforeLoginRedirect::fromPayload($request, $payload);
        }
        $loginRedirectEvent = $this->eventDispatcher->dispatch($loginRedirectEvent);

        if (!($loginRedirectEvent->getRedirectUri() instanceof UriInterface)) {
            $redirect = $loginRedirectEvent->getRedirectUri();
            /** @var UriInterface $uri */
            $uri = $request->getUri();
            if ($redirect[0] === '/') {
                $uri = $uri->withPath($redirect);
            }
            else
            {
                $parts = parse_url($redirect);
                if ($parts['host']) {
                    $uri = $uri->withHost($parts['host']);
                    $uri = $uri->withPath($parts['path']);
                }
            }
        }
        else {
            $uri = $loginRedirectEvent->getRedirectUri();
        }

        return $response->withHeader('Location', (string)$uri);
    }
}
