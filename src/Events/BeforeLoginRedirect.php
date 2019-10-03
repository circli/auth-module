<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Aura\Payload_Interface\PayloadInterface;
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
    /** @var PayloadInterface */
    private $payload;

    public static function fromAuthResponse(ServerRequestInterface $request, AuthResponse $authResponse): self
    {
        return new self($request, $authResponse);
    }

    public static function fromPayload(ServerRequestInterface $request, PayloadInterface $payload): self
    {
        $self = new self($request, null);
        $self->payload = $payload;
        return $self;
    }

    public function __construct(ServerRequestInterface $request, ?AuthResponse $authResponse)
    {
        $this->request = $request;
        $this->authResponse = $authResponse;
    }

    public function getPayload(): ?PayloadInterface
    {
        return $this->payload;
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
