<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountToken;

use Atlas\Mapper\MapperSelect;

/**
 * @method AccountTokenRecord|null fetchRecord()
 * @method AccountTokenRecord[] fetchRecords()
 * @method AccountTokenRecordSet fetchRecordSet()
 */
class AccountTokenSelect extends MapperSelect
{
}
