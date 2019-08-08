<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;
use Circli\Modules\Auth\Value\EmailAddress;
use Circli\Modules\Auth\Web\InputData\AuthMethodData;

interface AccountTokenRepositoryInterface
{
    /**
     * @param string $provider
     * @param string $uid
     * @return AccountTokenInterface[]
     */
    public function findByProviderAndUid(string $provider, string $uid);

    /**
     * @param AccountInterface $account
     * @return AccountTokenInterface[]
     */
    public function findByAccount(AccountInterface $account): array;

    public function findByAccountAndProvider(AccountInterface $account, string $provider): AccountTokenInterface;

    public function delete(AccountTokenInterface $accountToken): bool;

    public function save(AccountTokenInterface $accountToken): AccountTokenInterface;

    public function getHashIndex(EmailAddress $value): string;

    public function create(
        AccountInterface $account,
        string $provider,
        ?\DateTimeImmutable $expire
    ): AccountTokenInterface;

    public function createFromAuthData(AccountInterface $account, AuthMethodData $data): AccountTokenInterface;

    /**
     * @param string $provider
     * @return AccountTokenInterface[]
     */
    public function findExpiredByProvider(string $provider): array;

    /**
     * @return AccountTokenInterface[]
     */
    public function findExpired(): array;
}
