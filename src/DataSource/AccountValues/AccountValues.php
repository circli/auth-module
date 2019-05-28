<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountValues;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method AccountValuesTable getTable()
 * @method AccountValuesRelationships getRelationships()
 * @method AccountValuesRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountValuesRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AccountValuesRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountValuesRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AccountValuesRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountValuesRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AccountValuesSelect select(array $whereEquals = [])
 * @method AccountValuesRecord newRecord(array $fields = [])
 * @method AccountValuesRecord[] newRecords(array $fieldSets)
 * @method AccountValuesRecordSet newRecordSet(array $records = [])
 * @method AccountValuesRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AccountValuesRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AccountValues extends Mapper implements AccountValuesMapperInterface
{
}
