<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountAccess;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\PermissionInterface;

/**
 * @method AccountAccessTable getTable()
 * @method AccountAccessRelationships getRelationships()
 * @method AccountAccessRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountAccessRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AccountAccessRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountAccessRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AccountAccessRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountAccessRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AccountAccessSelect select(array $whereEquals = [])
 * @method AccountAccessRecord newRecord(array $fields = [])
 * @method AccountAccessRecord[] newRecords(array $fieldSets)
 * @method AccountAccessRecordSet newRecordSet(array $records = [])
 * @method AccountAccessRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AccountAccessRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AccountAccess extends Mapper implements AccountAccessMapperInterface
{
    public function addPermission(AccountInterface $account, PermissionInterface $permission)
    {
        // TODO: Implement addPermission() method.
    }

    public function deletePermission(AccountInterface $account, PermissionInterface $permission)
    {
        // TODO: Implement deletePermission() method.
    }
}
