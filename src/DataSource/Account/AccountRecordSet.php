<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\Account;

use Atlas\Mapper\RecordSet;

/**
 * @method AccountRecord offsetGet($offset)
 * @method AccountRecord appendNew(array $fields = [])
 * @method AccountRecord|null getOneBy(array $whereEquals)
 * @method AccountRecordSet getAllBy(array $whereEquals)
 * @method AccountRecord|null detachOneBy(array $whereEquals)
 * @method AccountRecordSet detachAllBy(array $whereEquals)
 * @method AccountRecordSet detachAll()
 * @method AccountRecordSet detachDeleted()
 */
class AccountRecordSet extends RecordSet
{
}
