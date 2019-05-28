<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountToken;

use Atlas\Mapper\RecordSet;

/**
 * @method AccountTokenRecord offsetGet($offset)
 * @method AccountTokenRecord appendNew(array $fields = [])
 * @method AccountTokenRecord|null getOneBy(array $whereEquals)
 * @method AccountTokenRecordSet getAllBy(array $whereEquals)
 * @method AccountTokenRecord|null detachOneBy(array $whereEquals)
 * @method AccountTokenRecordSet detachAllBy(array $whereEquals)
 * @method AccountTokenRecordSet detachAll()
 * @method AccountTokenRecordSet detachDeleted()
 */
class AccountTokenRecordSet extends RecordSet
{
}
