<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Atlas\Pdo\Connection;
use Circli\Extensions\Encryption\EncrypterInterface;
use Circli\Modules\Auth\DataSource\Account\Account;
use Circli\Modules\Auth\DataSource\Account\AccountMapperInterface;
use Circli\Modules\Auth\DataSource\AccountValues\AccountValues;
use Circli\Modules\Auth\DataSource\AccountValues\AccountValuesMapperInterface;
use Circli\Modules\Auth\Repositories\Enums\Status;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\Factory\AccountObjectFactory;
use Circli\Modules\Auth\Repositories\Objects\Factory\AccountValueObjectFactory;
use Circli\Modules\Auth\Repositories\Objects\ValueInterface;

final class AccountRepository implements AccountRepositoryInterface
{
    /** @var AccountObjectFactory */
    private $objectFactory;
    /** @var AccountValues|AccountValuesMapperInterface */
    private $valuesMapper;
    /** @var AccountValueObjectFactory */
    private $valueObjectFactory;
    /** @var EncrypterInterface */
    private $encrypter;
    /** @var Account|AccountMapperInterface */
    private $mapper;

    public function __construct(
        AccountMapperInterface $mapper,
        AccountObjectFactory $objectFactory,
        AccountValuesMapperInterface $valuesMapper,
        AccountValueObjectFactory $valueObjectFactory,
        EncrypterInterface $encrypter
    ) {
        $this->mapper = $mapper;
        $this->objectFactory = $objectFactory;
        $this->valuesMapper = $valuesMapper;
        $this->valueObjectFactory = $valueObjectFactory;
        $this->encrypter = $encrypter;
    }

    /**
     * Find account by id
     *
     * @param  int $accountId
     * @return AccountInterface
     * @throws AccountNotFound
     */
    public function findById(int $accountId): AccountInterface
    {
        $account = $this->mapper->fetchRecord($accountId, ['values']);
        if (!$account) {
            throw new AccountNotFound();
        }

        return $this->objectFactory->createObject($account);
    }

    public function refreshById(int $accountId): AccountInterface
    {
        $account = $this->mapper->fetchRecord($accountId, ['values']);
        if (!$account) {
            throw new AccountNotFound();
        }

        return $this->objectFactory->createObject($account, true);
    }

    /**
     * Persist account object
     * @param  AccountInterface $account
     * @return AccountInterface
     */
    public function save(AccountInterface $account): AccountInterface
    {
        $record = $this->objectFactory->getRecord($account);

        if ($record) {
            $this->mapper->persist($record);
        }

        return $account;
    }

    public function delete(AccountInterface $account): bool
    {
        $record = $this->objectFactory->getRecord($account);
        if (!$record) {
            return false;
        }
        $record->deleted = date('Y-m-d');
        $record->secret = '';
        $record->status = 'deleted';
        $this->mapper->persist($record);
        return true;
    }

    /**
     * Create new account
     *
     * @param string $secret
     * @param Status $status
     * @return AccountInterface
     */
    public function create(string $secret, Status $status): AccountInterface
    {
        $record = $this->mapper->newRecord();
        $record->status = (string) $status;
        $record->secret = $this->encrypter->encrypt($secret);
        $record->created = date('Y-m-d H:i:s');

        $this->mapper->insert($record);

        return $this->objectFactory->createObject($record);
    }

    /**
     * Add/Update an account value
     *
     * @param AccountInterface $account
     * @param ValueInterface $value
     * @return ValueInterface
     */
    public function addAccountValue(AccountInterface $account, ValueInterface $value): ValueInterface
    {
        $value = $value->withAccountId($account->getId());
        $currentRecord = $this->valueObjectFactory->sync($value, $account);
        if (!$currentRecord) {
            $currentRecord = $this->valuesMapper->fetchRecordBy([
                'account_id' => $account->getId(),
                'value_key' => $value->getKey()
            ]);
            if ($currentRecord) {
                $currentRecord = $this->valueObjectFactory->syncRecord($currentRecord, $value, $account);
            }
            else {
                $currentRecord = $this->valueObjectFactory->syncRecord(
                    $this->valuesMapper->newRecord([
                        'account_id' => $account->getId(),
                        'value_key' => $value->getKey(),
                    ]),
                    $value,
                    $account
                );
            }
        }

        $this->valuesMapper->persist($currentRecord);
        return $this->valueObjectFactory->createObject($currentRecord, $account);
    }

    /**
     * @param ValueInterface $value
     * @return bool
     */
    public function removeAccountValue(ValueInterface $value): bool
    {
        $currentRecord = $this->valuesMapper->fetchRecordBy([
            'account_id' => $value->getAccountId(),
            'value_key' => $value->getKey()
        ]);
        if (!$currentRecord) {
            return false;
        }
        $this->valuesMapper->delete($currentRecord);
        return true;
    }

    public function findByToken(Objects\AccountTokenInterface $token)
    {
        return $this->findById($token->getAccountId());
    }
}