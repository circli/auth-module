<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountRole;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\RoleInterface;

/**
 * @method AccountRoleTable getTable()
 * @method AccountRoleRelationships getRelationships()
 * @method AccountRoleRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountRoleRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AccountRoleRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountRoleRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AccountRoleRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountRoleRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AccountRoleSelect select(array $whereEquals = [])
 * @method AccountRoleRecord newRecord(array $fields = [])
 * @method AccountRoleRecord[] newRecords(array $fieldSets)
 * @method AccountRoleRecordSet newRecordSet(array $records = [])
 * @method AccountRoleRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AccountRoleRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AccountRole extends Mapper implements AccountRoleMapperInterface
{
    public function addNewRole(AccountInterface $account, int $roleId): AccountRoleRecord
    {
        $record = $this->newRecord([
            'account_id' => $account->getId(),
            'role_id' => $roleId,
        ]);
        $this->persist($record);
        return $record;
    }

    public function deleteRole(AccountInterface $account, int $roleId)
    {
        $record = $this->fetchRecordBy(['account_id' => $account->getId(), 'role_id' => $roleId]);
        if ($record) {
            $this->delete($record);
        }
    }
}
