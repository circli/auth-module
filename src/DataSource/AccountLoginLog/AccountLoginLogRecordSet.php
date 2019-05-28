<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountLoginLog;

use Atlas\Mapper\RecordSet;

/**
 * @method AccountLoginLogRecord offsetGet($offset)
 * @method AccountLoginLogRecord appendNew(array $fields = [])
 * @method AccountLoginLogRecord|null getOneBy(array $whereEquals)
 * @method AccountLoginLogRecordSet getAllBy(array $whereEquals)
 * @method AccountLoginLogRecord|null detachOneBy(array $whereEquals)
 * @method AccountLoginLogRecordSet detachAllBy(array $whereEquals)
 * @method AccountLoginLogRecordSet detachAll()
 * @method AccountLoginLogRecordSet detachDeleted()
 */
class AccountLoginLogRecordSet extends RecordSet
{
}
