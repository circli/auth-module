<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects\Factory;

use Atlas\Mapper\Record;
use Circli\Extensions\Encryption\EncrypterInterface;
use Circli\Extensions\Encryption\KeyFactory;
use Circli\Modules\Auth\DataSource\Account\AccountRecord;
use Circli\Modules\Auth\Repositories\Enums\Status;
use Circli\Modules\Auth\Repositories\Objects\Account;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use ParagonIE\Halite\HiddenString;

final class AccountObjectFactory implements ObjectFactoryInterface
{
    /** @var AccountValueObjectFactory */
    private $valueObjectFactory;
    /** @var EncrypterInterface */
    private $encrypter;
    /** @var AccountRecord[] */
    private $recordMap = [];
    /** @var AccountInterface[] */
    private $objectMap = [];

    public function __construct(AccountValueObjectFactory $valueObjectFactory, EncrypterInterface $encrypter)
    {
        $this->encrypter = $encrypter;
        $this->valueObjectFactory = $valueObjectFactory;
    }

    public function createCollection(iterable $recordSet): array
    {
        $collection = [];
        foreach ($recordSet as $record) {
            $collection[] = $this->createObject($record);
        }

        return $collection;
    }

    /**
     * @param AccountRecord|Record $record
     * @return AccountInterface
     */
    public function createObject(Record $record, bool $recreate = false): AccountInterface
    {
        if (!$recreate && isset($this->objectMap[$record->id])) {
            return $this->objectMap[$record->id];
        }

        $accountKey = KeyFactory::deriveEncryptionKeyFromSecret(new HiddenString($this->encrypter->decrypt($record->secret)));
        $this->objectMap[$record->id] = new Account((int) $record->id, Status::fromValue($record->status), $accountKey);
        $this->recordMap[$this->objectMap[$record->id]->getId()] = $record;

        $values = [];
        if ($record->values) {
            $values = $this->valueObjectFactory->createCollection($record->values, $this->objectMap[$record->id]);
        }
        $this->objectMap[$record->id]->setValues($values);
        return $this->objectMap[$record->id];
    }

    /**
     * @param $object
     * @return Record|AccountRecord|null
     */
    public function getRecord($object): ?Record
    {
        if ($object instanceof AccountInterface) {
            $record = $this->recordMap[$object->getId()];
            if ($record) {
                $record->status = $object->getStatus();
            }
            return $record;
        }
        return null;
    }
}
