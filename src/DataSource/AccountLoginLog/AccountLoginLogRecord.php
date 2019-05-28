<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountLoginLog;

use Atlas\Mapper\Record;

/**
 * @method AccountLoginLogRow getRow()
 */
class AccountLoginLogRecord extends Record
{
    use AccountLoginLogFields;
}
