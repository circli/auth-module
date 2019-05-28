<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\Account;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method AccountTable getTable()
 * @method AccountRelationships getRelationships()
 * @method AccountRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AccountRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AccountRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AccountSelect select(array $whereEquals = [])
 * @method AccountRecord newRecord(array $fields = [])
 * @method AccountRecord[] newRecords(array $fieldSets)
 * @method AccountRecordSet newRecordSet(array $records = [])
 * @method AccountRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AccountRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Account extends Mapper implements AccountMapperInterface
{
}
