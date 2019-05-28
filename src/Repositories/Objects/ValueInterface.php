<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

interface ValueInterface
{
    public function getAccountId(): int;

    public function getKey(): string;

    public function getValue();

    public function setValue($value);

    public function isEncrypted();

    public function enableEncryption();

    public function disableEncryption();

    /**
     * @param int $accountId
     * @return static
     */
    public function withAccountId(int $accountId);
}