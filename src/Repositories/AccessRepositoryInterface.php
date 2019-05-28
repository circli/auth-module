<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AclInterface;
use Circli\Modules\Auth\Repositories\Objects\PermissionInterface;
use Circli\Modules\Auth\Repositories\Objects\RoleInterface;

interface AccessRepositoryInterface
{
    public function buildAcl(AccountInterface $account): AclInterface;

    public function addAccountRole(AccountInterface $account, RoleInterface $role): bool;
    public function removeAccountRole(AccountInterface $account, RoleInterface $role): bool;

    public function addAccountPermission(AccountInterface $account, PermissionInterface $permission): bool;
    public function removeAccountPermission(AccountInterface $account, PermissionInterface $permission): bool;

    public function createRole(string $role): RoleInterface;

    public function addRolePermission(RoleInterface $role, PermissionInterface $permission): bool;
    public function removeRolePermission(RoleInterface $role, PermissionInterface $permission): bool;

    /**
     * @return RoleInterface[]
     */
    public function findAllRoles(): array;
}