<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountRole;

use Atlas\Table\Row;

/**
 * @property mixed $account_id bigint(20,0) unsigned NOT NULL
 * @property mixed $role_id bigint(20,0) unsigned NOT NULL
 */
class AccountRoleRow extends Row
{
    protected $cols = [
        'account_id' => null,
        'role_id' => null,
    ];
}