<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\Account;

use Atlas\Mapper\Record;

/**
 * @method AccountRow getRow()
 */
class AccountRecord extends Record
{
    use AccountFields;
}
