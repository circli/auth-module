<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AclRole;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method AclRoleTable getTable()
 * @method AclRoleRelationships getRelationships()
 * @method AclRoleRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AclRoleRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AclRoleRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AclRoleRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AclRoleRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AclRoleRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AclRoleSelect select(array $whereEquals = [])
 * @method AclRoleRecord newRecord(array $fields = [])
 * @method AclRoleRecord[] newRecords(array $fieldSets)
 * @method AclRoleRecordSet newRecordSet(array $records = [])
 * @method AclRoleRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AclRoleRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AclRole extends Mapper
{
}
