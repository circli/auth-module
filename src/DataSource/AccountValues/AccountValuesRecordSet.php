<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountValues;

use Atlas\Mapper\RecordSet;

/**
 * @method AccountValuesRecord offsetGet($offset)
 * @method AccountValuesRecord appendNew(array $fields = [])
 * @method AccountValuesRecord|null getOneBy(array $whereEquals)
 * @method AccountValuesRecordSet getAllBy(array $whereEquals)
 * @method AccountValuesRecord|null detachOneBy(array $whereEquals)
 * @method AccountValuesRecordSet detachAllBy(array $whereEquals)
 * @method AccountValuesRecordSet detachAll()
 * @method AccountValuesRecordSet detachDeleted()
 */
class AccountValuesRecordSet extends RecordSet
{
}
