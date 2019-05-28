<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountAccess;

use Atlas\Mapper\RecordSet;

/**
 * @method AccountAccessRecord offsetGet($offset)
 * @method AccountAccessRecord appendNew(array $fields = [])
 * @method AccountAccessRecord|null getOneBy(array $whereEquals)
 * @method AccountAccessRecordSet getAllBy(array $whereEquals)
 * @method AccountAccessRecord|null detachOneBy(array $whereEquals)
 * @method AccountAccessRecordSet detachAllBy(array $whereEquals)
 * @method AccountAccessRecordSet detachAll()
 * @method AccountAccessRecordSet detachDeleted()
 */
class AccountAccessRecordSet extends RecordSet
{
}
