<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Circli\Modules\Auth\Repositories\Enums\Status;
use Circli\Modules\Auth\Repositories\Exception\AccountNotFound;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AclInterface;
use Circli\Modules\Auth\Repositories\Objects\ValueInterface;

interface AccountRepositoryInterface
{
    /**
     * Find account by id
     *
     * @param  int  $accountId
     * @return AccountInterface
     * @throws AccountNotFound
     */
    public function findById(int $accountId): AccountInterface;

    /**
     * Re-fetch account by id
     *
     * @param  int  $accountId
     * @return AccountInterface
     * @throws AccountNotFound
     */
    public function refreshById(int $accountId): AccountInterface;

    /**
     * Persist account object
     * @param  AccountInterface $account
     * @return AccountInterface
     */
    public function save(AccountInterface $account): AccountInterface;

    public function delete(AccountInterface $account): bool;

    /**
     * Create new account
     *
     * @param string $secret
     * @param Status $status
     * @return AccountInterface
     */
    public function create(string $secret, Status $status): AccountInterface;

    /**
     * Add/Update an account value
     *
     * @param AccountInterface $account
     * @param ValueInterface $value
     * @return ValueInterface
     */
    public function addAccountValue(AccountInterface $account, ValueInterface $value): ValueInterface;

    /**
     * @param ValueInterface $value
     * @return bool
     */
    public function removeAccountValue(ValueInterface $value): bool;

    public function findByToken(Objects\AccountTokenInterface $token);
}
