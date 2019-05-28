<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountLoginLog;

use Atlas\Table\Table;

/**
 * @method AccountLoginLogRow|null fetchRow($primaryVal)
 * @method AccountLoginLogRow[] fetchRows(array $primaryVals)
 * @method AccountLoginLogTableSelect select(array $whereEquals = [])
 * @method AccountLoginLogRow newRow(array $cols = [])
 * @method AccountLoginLogRow newSelectedRow(array $cols)
 */
class AccountLoginLogTable extends Table
{
    const DRIVER = 'mysql';

    const NAME = 'account_login_log';

    const COLUMNS = [
        'id' => [
            'name' => 'id',
            'type' => 'bigint unsigned',
            'size' => 20,
            'scale' => 0,
            'notnull' => true,
            'default' => null,
            'autoinc' => true,
            'primary' => true,
            'options' => null,
        ],
        'account_id' => [
            'name' => 'account_id',
            'type' => 'bigint unsigned',
            'size' => 20,
            'scale' => 0,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'login_time' => [
            'name' => 'login_time',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'ip' => [
            'name' => 'ip',
            'type' => 'text',
            'size' => 65535,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'ip_idx' => [
            'name' => 'ip_idx',
            'type' => 'text',
            'size' => 65535,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'ua' => [
            'name' => 'ua',
            'type' => 'int',
            'size' => 10,
            'scale' => 0,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'provider' => [
            'name' => 'provider',
            'type' => 'varchar',
            'size' => 64,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'status' => [
            'name' => 'status',
            'type' => 'varchar',
            'size' => 64,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'id',
        'account_id',
        'login_time',
        'ip',
        'ip_idx',
        'ua',
        'provider',
        'status',
    ];

    const COLUMN_DEFAULTS = [
        'id' => null,
        'account_id' => null,
        'login_time' => null,
        'ip' => null,
        'ip_idx' => null,
        'ua' => null,
        'provider' => null,
        'status' => null,
    ];

    const PRIMARY_KEY = [
        'id',
    ];

    const AUTOINC_COLUMN = 'id';

    const AUTOINC_SEQUENCE = null;
}