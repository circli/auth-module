<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

use Circli\Modules\Auth\Repositories\Enums\Status;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\EncryptionKey;

final class GuestAccount implements AccountInterface
{
    private $parentAccount;

    public function isGuest(): bool
    {
        return true;
    }

    public function getId(): int
    {
        return 0;
    }

    public function setParentAccount(AccountInterface $parent)
    {
        $this->parentAccount = $parent;
    }

    public function getParentAccount(): ?AccountInterface
    {
        return $this->parentAccount;
    }

    public function getStatus(): Status
    {
        return Status::TEMPORARY();
    }

    public function getAccountKey(): EncryptionKey
    {
        return KeyFactory::generateEncryptionKey();
    }

    public function setValues(iterable $values)
    {
        // guest don't have values
    }

    public function getValues(): iterable
    {
        return [];
    }

    public function getValue(string $key)
    {
        return null;
    }

    public function haveRole(string $role): bool
    {
        return false;
    }

    public function setRoles(array $roles): void
    {
        // guest don't have roles
    }
}