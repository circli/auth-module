<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Session;

use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;

final class PhpSession implements AuthSessionInterface
{
    private const SESSION_KEY = 'circli_auth_session';
    
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function destroy(): bool
    {
        unset($_SESSION[self::SESSION_KEY]);
        return session_regenerate_id();
    }

    public function persist(AccountTokenInterface $token): bool
    {
        session_regenerate_id();
        $_SESSION[self::SESSION_KEY]['auth_token'] = (string)$token;
        return true;
    }

    public function getSessionData(): ?array
    {
        $value = $_SESSION[self::SESSION_KEY]['auth_token'] ?? null;
        if ($value && strpos($value, '.')) {
            return explode('.', $value, 2);
        }
        return null;
    }

    public function jsonSerialize()
    {
        return $this->getSessionData();
    }
}
