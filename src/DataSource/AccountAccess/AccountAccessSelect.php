<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountAccess;

use Atlas\Mapper\MapperSelect;

/**
 * @method AccountAccessRecord|null fetchRecord()
 * @method AccountAccessRecord[] fetchRecords()
 * @method AccountAccessRecordSet fetchRecordSet()
 */
class AccountAccessSelect extends MapperSelect
{
}
