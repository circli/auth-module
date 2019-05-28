<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\Account;

use Atlas\Table\Row;

/**
 * @property mixed $id bigint(20,0) unsigned NOT NULL
 * @property mixed $secret text(65535) NOT NULL
 * @property mixed $created datetime NOT NULL
 * @property mixed $deleted datetime
 * @property mixed $status enum(9) NOT NULL
 */
class AccountRow extends Row
{
    protected $cols = [
        'id' => null,
        'secret' => null,
        'created' => null,
        'deleted' => null,
        'status' => null,
    ];
}