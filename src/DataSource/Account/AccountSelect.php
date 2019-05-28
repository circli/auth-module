<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\Account;

use Atlas\Mapper\MapperSelect;

/**
 * @method AccountRecord|null fetchRecord()
 * @method AccountRecord[] fetchRecords()
 * @method AccountRecordSet fetchRecordSet()
 */
class AccountSelect extends MapperSelect
{
}
