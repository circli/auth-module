<?php

namespace Circli\Modules\Auth\DataSource\AccountAccess;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\PermissionInterface;

/**
 * @method AccountAccessRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountAccessRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountAccessRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountAccessSelect select(array $whereEquals = [])
 * @method AccountAccessRecord newRecord(array $fields = [])
 */
interface AccountAccessMapperInterface
{
    public function addPermission(AccountInterface $account, PermissionInterface $permission);

    public function deletePermission(AccountInterface $account, PermissionInterface $permission);
}