<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects\Factory;

use Atlas\Mapper\Record;
use Circli\Extensions\Encryption\FieldEncrypterFactoryInterface;
use Circli\Modules\Auth\DataSource\AccountValues\AccountValuesRecord;
use Circli\Modules\Auth\Repositories\EncryptionFields\AccountValueField;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\Value;
use Circli\Modules\Auth\Repositories\Objects\ValueInterface;
use ParagonIE\Halite\HiddenString;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

final class AccountValueObjectFactory
{
    /** @var AccountValuesRecord[] */
    private $recordMap = [];
    /** @var ValueInterface[] */
    private $objectMap = [];
    /** @var FieldEncrypterFactoryInterface */
    private $fieldEncrypterFactory;

    public function __construct(FieldEncrypterFactoryInterface $fieldEncrypterFactory)
    {
        $this->fieldEncrypterFactory = $fieldEncrypterFactory;
    }

    public function createCollection(iterable $recordSet, AccountInterface $account): iterable
    {
        $collection = [];
        foreach ($recordSet as $record) {
            $collection[] = $this->createObject($record, $account);
        }

        return $collection;
    }

    /**
     * @param AccountValuesRecord|Record $record
     * @param AccountInterface $account
     * @return ValueInterface
     */
    public function createObject(Record $record, AccountInterface $account): ValueInterface
    {
        $key = $this->getKeyFromRecord($record);

        if (isset($this->objectMap[$key])) {
            return $this->objectMap[$key];
        }

        $value = $record->value_data;

        if ($record->encrypted) {
            $value = Symmetric::decrypt($value, $account->getAccountKey())->getString();
        }

        $value = json_decode($value, true);

        $this->objectMap[$key] = new Value(
            $record->value_key,
            $value,
            (bool) $record->encrypted,
            (int) $record->account_id
        );

        $this->recordMap[$key] = $record;
        return $this->objectMap[$key];
    }

    /**
     * @param $object
     * @return Record|AccountValuesRecord|null
     */
    public function getRecord($object): ?Record
    {
        if ($object instanceof ValueInterface) {
            $key = $this->getKeyFromObject($object);
            return $this->recordMap[$key] ?? null;
        }
        return null;
    }

    public function sync(ValueInterface $value, AccountInterface $account): ?AccountValuesRecord
    {
        $record = $this->getRecord($value);
        if (!$record) {
            return null;
        }
        return $this->syncRecord($record, $value, $account);
    }

    public function syncRecord(AccountValuesRecord $record, ValueInterface $value, AccountInterface $account): AccountValuesRecord
    {
        $record->encrypted = (int) $value->isEncrypted();
        if ($record->encrypted) {
            $record->value_data = Symmetric::encrypt(new HiddenString(json_encode($value->getValue())), $account->getAccountKey());
            if (is_string($value->getValue()) && $value->getValue()) {
                $record->value_idx = $this->getValueIndex($value);
            }
        }
        else {
            $record->value_data = json_encode($value->getValue());
        }

        $record->modified = date('Y-m-d H:i:s');

        return $record;
    }

    public function getValueIndex(ValueInterface $value): string
    {
        return $this->fieldEncrypterFactory
            ->getFieldEncrypter(AccountValueField::class)
            ->getBlindIndex($value->getValue());
    }

    private function getKeyFromRecord(AccountValuesRecord $record): string
    {
        return $record->account_id . '.' . $record->value_key;
    }

    private function getKeyFromObject(ValueInterface $record): string
    {
        return $record->getAccountId() . '.' . $record->getKey();
    }
}
