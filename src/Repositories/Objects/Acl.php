<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

class Acl implements AclInterface
{
    /** @var AccountInterface */
    private $account;
    /** @var array */
    private $accountRoles;
    /** @var array */
    private $accountAcl;

    public function __construct(AccountInterface $account, array $accountRoles, array $accountAcl)
    {
        $this->account = $account;
        $this->accountRoles = $accountRoles;
        $this->accountAcl = $accountAcl;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function hasRole(string $role): bool
    {
        return \in_array($role, $this->accountRoles, true);
    }

    public function isAllowed(string $key): bool
    {
        if (isset($this->accountAcl[$key]) && $this->accountAcl[$key] === 'approved') {
            return true;
        }

       if (isset($this->accountAcl['*']) && $this->accountAcl['*'] === 'approved') {
            return true;
        }

        return false;
    }

    public function isOwner($obj): bool
    {
        if (\is_array($obj) && isset($obj['account_id'])) {
            return (int)$obj['account_id'] === $this->account->getId();
        }

        if (\is_object($obj)) {
            if (method_exists($obj, 'isOwner')) {
                return $obj->isOwner($this->account);
            }

            if (isset($obj->account_id)) {
                return $obj->account_id === $this->account->getId();
            }

            if (method_exists($obj, 'getAccountId')) {
                return $obj->getAccountId() === $this->account->getId();
            }
        }

        return false;
    }
}
