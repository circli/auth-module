<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AclRoleAcces;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method AclRoleAccesTable getTable()
 * @method AclRoleAccesRelationships getRelationships()
 * @method AclRoleAccesRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AclRoleAccesRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AclRoleAccesRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AclRoleAccesRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AclRoleAccesRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AclRoleAccesRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AclRoleAccesSelect select(array $whereEquals = [])
 * @method AclRoleAccesRecord newRecord(array $fields = [])
 * @method AclRoleAccesRecord[] newRecords(array $fieldSets)
 * @method AclRoleAccesRecordSet newRecordSet(array $records = [])
 * @method AclRoleAccesRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AclRoleAccesRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AclRoleAcces extends Mapper
{
}
