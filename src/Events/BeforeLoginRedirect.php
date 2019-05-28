<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Authentication\AuthResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

final class BeforeLoginRedirect
{
    /** @var ServerRequestInterface */
    private $request;
    /** @var AuthResponse|null */
    private $authResponse;
    /** @var string|UriInterface */
    private $redirect = '/';

    public function __construct(ServerRequestInterface $request, ?AuthResponse $authResponse)
    {
        $this->request = $request;
        $this->authResponse = $authResponse;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getAuthResponse(): ?AuthResponse
    {
        return $this->authResponse;
    }

    /**
     * @param string|UriInterface $uri
     */
    public function setRedirectUri($uri): void
    {
        $this->redirect = $uri;
    }

    /**
     * @return UriInterface|string
     */
    public function getRedirectUri()
    {
        return $this->redirect;
    }
}
