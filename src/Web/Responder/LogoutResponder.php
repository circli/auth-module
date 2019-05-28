<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Circli\Modules\Auth\Session\Factory as AuthSessionFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LogoutResponder
{
    /** @var AuthSessionFactory */
    private $authSessionFactory;

    public function __construct(AuthSessionFactory $authSessionFactory)
    {
        $this->authSessionFactory = $authSessionFactory;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        PayloadInterface $payload
    ): ResponseInterface {
        $authSession = $this->authSessionFactory->fromRequest($request);
        $authSession->destroy();

        $response = $response->withStatus(303);
        return $response->withHeader('Location', '/');
    }
}
