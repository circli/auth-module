<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AclRoleAcces;

use Atlas\Table\Row;

/**
 * @property mixed $role_id bigint(20,0) unsigned NOT NULL
 * @property mixed $access_key varchar(64) NOT NULL
 * @property mixed $access enum(8) NOT NULL
 * @property mixed $modified datetime NOT NULL
 */
class AclRoleAccesRow extends Row
{
    protected $cols = [
        'role_id' => null,
        'access_key' => null,
        'access' => 'denied',
        'modified' => null,
    ];
}
