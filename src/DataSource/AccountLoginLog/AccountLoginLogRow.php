<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountLoginLog;

use Atlas\Table\Row;

/**
 * @property mixed $id bigint(20,0) unsigned NOT NULL
 * @property mixed $account_id bigint(20,0) unsigned NOT NULL
 * @property mixed $login_time datetime NOT NULL
 * @property mixed $ip text(65535) NOT NULL
 * @property mixed $ip_idx text(65535) NOT NULL
 * @property mixed $ua int(10,0) NOT NULL
 * @property mixed $provider varchar(64)
 * @property mixed $status varchar(64)
 */
class AccountLoginLogRow extends Row
{
    protected $cols = [
        'id' => null,
        'account_id' => null,
        'login_time' => null,
        'ip' => null,
        'ip_idx' => null,
        'ua' => null,
        'provider' => null,
        'status' => null,
    ];
}