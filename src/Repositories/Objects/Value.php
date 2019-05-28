<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

final class Value implements ValueInterface
{
    /** @var string */
    private $key;
    private $data;
    /** @var bool */
    private $encrypted;
    /** @var int */
    private $accountId;
    /** @var int */
    private $id;

    public function __construct(string $key, $data, bool $encrypted = false, int $accountId = null, int $id = null)
    {
        $this->key = $key;
        $this->data = $data;
        $this->encrypted = $encrypted;
        $this->accountId = $accountId;
        $this->id = $id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->data;
    }

    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function setValue($value)
    {
        $this->data = $value;
    }

    public function enableEncryption()
    {
        $this->encrypted = true;
    }

    public function disableEncryption()
    {
        $this->encrypted = false;
    }

    public function withAccountId(int $accountId): self
    {
        $self = clone $this;
        $self->accountId = $accountId;
        return $self;
    }
}
