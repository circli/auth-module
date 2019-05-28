<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountLoginLog;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;
use Circli\Extensions\Encryption\FieldEncrypterAwareInterface;
use Circli\Extensions\Encryption\FieldEncrypterAwareTrait;
use Circli\Modules\Auth\Repositories\EncryptionFields\IpField;

/**
 * @method AccountLoginLogTable getTable()
 * @method AccountLoginLogRelationships getRelationships()
 * @method AccountLoginLogRecord|null fetchRecord($primaryVal, array $with = [])
 * @method AccountLoginLogRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method AccountLoginLogRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method AccountLoginLogRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method AccountLoginLogRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method AccountLoginLogRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method AccountLoginLogSelect select(array $whereEquals = [])
 * @method AccountLoginLogRecord newRecord(array $fields = [])
 * @method AccountLoginLogRecord[] newRecords(array $fieldSets)
 * @method AccountLoginLogRecordSet newRecordSet(array $records = [])
 * @method AccountLoginLogRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method AccountLoginLogRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class AccountLoginLog extends Mapper implements LoginLogMapperInterface, FieldEncrypterAwareInterface
{
    use FieldEncrypterAwareTrait;

    public function createNewRecord(
        int $accountId,
        \DateTimeImmutable $loginTime,
        string $ip,
        string $ua,
        string $provider,
        string $status
    ): bool {
        $record = $this->newRecord();
        $record->account_id = $accountId;
        $record->login_time = $loginTime->format('Y-m-d H:i:s');
        $encrypter = $this->getFieldEncrypter(IpField::class);
        $record->ip = $encrypter->encrypt($ip);
        $record->ip_idx = $encrypter->getBlindIndex($ip);
        $record->ua = $ua;
        $record->provider = $provider;
        $record->status = $status;

        try {
            $this->insert($record);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
