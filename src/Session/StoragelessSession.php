<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Session;

use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;
use PSR7Sessions\Storageless\Session\SessionInterface;

final class StoragelessSession implements AuthSessionInterface
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function destroy(): bool
    {
        $this->session->clear();
        return true;
    }

    public function persist(AccountTokenInterface $token): bool
    {
        $this->session->set('auth_token', (string)$token);
        return true;
    }

    public function getSessionData(): ?array
    {
        $value = $this->session->get('auth_token');
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
