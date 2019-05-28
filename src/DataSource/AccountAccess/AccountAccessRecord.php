<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountAccess;

use Atlas\Mapper\Record;

/**
 * @method AccountAccessRow getRow()
 */
class AccountAccessRecord extends Record
{
    use AccountAccessFields;
}
