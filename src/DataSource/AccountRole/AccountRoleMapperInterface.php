<?php

namespace Circli\Modules\Auth\DataSource\AccountRole;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\RoleInterface;

interface AccountRoleMapperInterface
{
    public function addNewRole(AccountInterface $account, int $roleId);

    public function deleteRole(AccountInterface $account, int $getRoleId);
}