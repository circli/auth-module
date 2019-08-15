<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Value;

use Circli\WebCore\Contracts\CookieInterface;

final class RawTokenValue
{
    /** @var string */
    private $uid;
    /** @var string */
    private $token;

    public static function fromCookie(CookieInterface $cookie): self
    {
        [$uid, $token] = explode('.', $cookie->getValue());

        return new self($uid, $token);
    }

    private function __construct(string $uid, string $token)
    {
        $this->uid = $uid;
        $this->token = $token;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
