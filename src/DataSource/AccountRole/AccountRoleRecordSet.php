<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountRole;

use Atlas\Mapper\RecordSet;

/**
 * @method AccountRoleRecord offsetGet($offset)
 * @method AccountRoleRecord appendNew(array $fields = [])
 * @method AccountRoleRecord|null getOneBy(array $whereEquals)
 * @method AccountRoleRecordSet getAllBy(array $whereEquals)
 * @method AccountRoleRecord|null detachOneBy(array $whereEquals)
 * @method AccountRoleRecordSet detachAllBy(array $whereEquals)
 * @method AccountRoleRecordSet detachAll()
 * @method AccountRoleRecordSet detachDeleted()
 */
class AccountRoleRecordSet extends RecordSet
{
}
