<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Data;

final class TokenLoginData implements TokenLoginDataInterface
{
    /** @var string */
    private $tokenProvider;
    /** @var string */
    private $tokenUid;
    /** @var string */
    private $token;
    /** @var string */
    private $ip;
    /** @var string */
    private $userAgent;

    public function __construct(string $tokenProvider, string $tokenUid, string $token, string $ip, string $userAgent)
    {
        $this->tokenProvider = $tokenProvider;
        $this->tokenUid = $tokenUid;
        $this->token = $token;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    public function getProvider(): string
    {
        return 'token';
    }

    public function getTokenProvider(): string
    {
        return $this->tokenProvider;
    }

    public function getTokenUid(): string
    {
        return $this->tokenUid;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}