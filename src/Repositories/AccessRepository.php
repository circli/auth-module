<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Atlas\Pdo\Connection;
use Circli\Modules\Auth\DataSource\AccountAccess\AccountAccessMapperInterface;
use Circli\Modules\Auth\DataSource\AccountRole\AccountRoleMapperInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AclInterface;
use Circli\Modules\Auth\Repositories\Objects\PermissionInterface;
use Circli\Modules\Auth\Repositories\Objects\RoleInterface;

final class AccessRepository implements AccessRepositoryInterface
{
    /** @var AccountRoleMapperInterface */
    protected $accountRoleMapper;
    /** @var Connection */
    private $connection;
    /** @var AccountAccessMapperInterface */
    private $accessMapper;

    public function __construct(
        AccountAccessMapperInterface $accessMapper,
        AccountRoleMapperInterface $accountRoleMapper,
        Connection $connection
    ) {
        $this->accessMapper = $accessMapper;
        $this->connection = $connection;
        $this->accountRoleMapper = $accountRoleMapper;
    }

    /** @var AclInterface[] */
    private $aclCache = [];

    public function buildAcl(AccountInterface $account): AclInterface
    {
        if (isset($this->aclCache[$account->getId()])) {
            return $this->aclCache[$account->getId()];
        }

        $sql = "SELECT aa.access_key, aa.access, '' as role 
                FROM `account_access` aa 
                WHERE aa.account_id = :id
                  UNION 
                SELECT ra.access_key, ra.access, r.name as role 
                FROM account_roles ar 
                LEFT JOIN acl_role_access ra ON ar.role_id = ra.role_id 
                LEFT JOIN acl_roles r ON ar.role_id = r.id 
                WHERE ar.account_id = :id";

        $stmt = $this->connection->perform($sql, [
            'id' => $account->getId()
        ]);
        $rawAccess = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $list = [];
        $roles = [];
        foreach ($rawAccess as $access) {
            if (!\in_array($access['role'], $roles, true)) {
                $roles[] = $access['role'];
            }

            if (isset($list[$access['key']])) {
                if ($access['access'] === 'denied') {
                    $list[$access['key']] = $access['access'];
                }
            } else {
                $list[$access['key']] = $access['access'];
            }
        }

        return $this->aclCache[$account->getId()] = new Acl($account, $list, $roles);
    }

    public function addAccountRole(AccountInterface $account, RoleInterface $role): bool
    {
        try {
            $this->accountRoleMapper->addNewRole($account, $role->getId());
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function removeAccountRole(AccountInterface $account, RoleInterface $role): bool
    {
        try {
            $this->accountRoleMapper->deleteRole($account, $role->getId());
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function addAccountPermission(AccountInterface $account, PermissionInterface $permission): bool
    {
        try {
            $this->accessMapper->addPermission($account, $permission);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function removeAccountPermission(AccountInterface $account, PermissionInterface $permission): bool
    {
        try {
            $this->accessMapper->deletePermission($account, $permission);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function createRole(string $role): RoleInterface
    {
        
    }

    public function addRolePermission(RoleInterface $role, PermissionInterface $permission): bool
    {
        return false;
    }

    public function removeRolePermission(RoleInterface $role, PermissionInterface $permission): bool
    {
        return false;
    }

    /**
     * @return RoleInterface[]
     */
    public function findAllRoles(): array
    {
        // TODO: Implement findAllRoles() method.
    }
}