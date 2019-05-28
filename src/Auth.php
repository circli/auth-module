<?php declare(strict_types=1);

namespace Circli\Modules\Auth;

use Circli\Modules\Auth\Events\AccessDenied;
use Circli\Modules\Auth\Events\AclAccessRequest;
use Circli\Modules\Auth\Events\OwnerAccessRequest;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AclInterface;
use Circli\Modules\Auth\Voter\AccessRequestEventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class Auth implements AclInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    /** @var AccountInterface */
    private $account;

    public function __construct(AccountInterface $account, EventDispatcherInterface $eventDispatcher)
    {
        $this->account = $account;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function isAuthenticated(): bool
    {
        return !$this->account->isGuest();
    }

    public function hasParentAccount(): bool
    {
        return $this->account->getParentAccount() !== null;
    }
    
    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function isAllowed(string $key): bool
    {
        /** @var AclAccessRequest $event */
        $event = $this->eventDispatcher->dispatch(new AclAccessRequest($key));
        if (!$event->allowed()) {
            $this->eventDispatcher->dispatch(new AccessDenied($this->account, AccessDenied::PERMISSION, $key));
        }
        return $event->allowed();
    }

    public function haveAccess(AccessRequestEventInterface $key): bool
    {
        /** @var AclAccessRequest $event */
        $event = $this->eventDispatcher->dispatch($key);
        return $event->allowed();
    }

    public function isOwner(object $obj): bool
    {
        /** @var OwnerAccessRequest $event */
        $event = $this->eventDispatcher->dispatch(new OwnerAccessRequest($obj));
        if (!$event->allowed()) {
            $this->eventDispatcher->dispatch(new AccessDenied($this->account, AccessDenied::OWNER, $obj));
        }
        return $event->allowed();
    }
}
