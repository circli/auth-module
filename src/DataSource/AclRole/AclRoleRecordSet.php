<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AclRole;

use Atlas\Mapper\RecordSet;

/**
 * @method AclRoleRecord offsetGet($offset)
 * @method AclRoleRecord appendNew(array $fields = [])
 * @method AclRoleRecord|null getOneBy(array $whereEquals)
 * @method AclRoleRecordSet getAllBy(array $whereEquals)
 * @method AclRoleRecord|null detachOneBy(array $whereEquals)
 * @method AclRoleRecordSet detachAllBy(array $whereEquals)
 * @method AclRoleRecordSet detachAll()
 * @method AclRoleRecordSet detachDeleted()
 */
class AclRoleRecordSet extends RecordSet
{
}
