<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

use Circli\Modules\Auth\Repositories\Enums\Status;
use ParagonIE\Halite\Symmetric\EncryptionKey;

interface AccountInterface
{
    public function isGuest(): bool;

    public function getId(): int;

    public function setParentAccount(AccountInterface $parent);

    public function getParentAccount(): ?AccountInterface;

    public function getStatus(): Status;

    public function getAccountKey(): EncryptionKey;

    public function setValues(iterable $values);

    /**
     * @return ValueInterface[]
     */
    public function getValues(): iterable;

    public function getValue(string $key);

    public function setRoles(array $roles): void;

    public function haveRole(string $role): bool;
}