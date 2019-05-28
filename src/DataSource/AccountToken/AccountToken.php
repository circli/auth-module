<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountToken;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method AccountTokenTable getTable()
 * @method AccountTokenRelationships getRelationships()
 * @method AccountTokenRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountTokenRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AccountTokenRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountTokenRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AccountTokenRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountTokenRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AccountTokenSelect select(array $whereEquals = [])
 * @method AccountTokenRecord newRecord(array $fields = [])
 * @method AccountTokenRecord[] newRecords(array $fieldSets)
 * @method AccountTokenRecordSet newRecordSet(array $records = [])
 * @method AccountTokenRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AccountTokenRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AccountToken extends Mapper implements AccountTokenMapperInterface
{
    public function getTableName(): string
    {
        return $this->getTable()::NAME;
    }
}
