<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

use Circli\Modules\Auth\Repositories\Enums\Status;
use ParagonIE\Halite\Symmetric\EncryptionKey;

final class Account implements AccountInterface
{
    /** @var Status */
    private $status;
    /** @var EncryptionKey */
    private $accountKey;
    /** @var int */
    private $id;
    /** @var AccountInterface|null */
    private $parent;
    /** @var ValueInterface[] */
    private $values = [];

    public function __construct(int $id, Status $status, EncryptionKey $accountKey)
    {
        $this->id = $id;
        $this->status = $status;
        $this->accountKey = $accountKey;
    }

    public function getAccountKey(): EncryptionKey
    {
        return $this->accountKey;
    }

    public function isGuest(): bool
    {
        return false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setParentAccount(AccountInterface $parent)
    {
        $this->parent = $parent;
    }

    public function getParentAccount(): ?AccountInterface
    {
        return $this->parent;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setValues(iterable $values)
    {
        $this->values = $values;
    }

    /**
     * @return ValueInterface[]
     */
    public function getValues(): iterable
    {
        return $this->values;
    }

    /**
     * @param string $key
     * @return ValueInterface|null
     */
    public function getValue(string $key): ?ValueInterface
    {
        foreach ($this->values as $value) {
            if ($value->getKey() === $key) {
                return $value;
            }
        }
        return null;
    }
}
