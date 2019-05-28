<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

use Circli\Modules\Auth\Value\Hash;

interface AccountTokenInterface
{
    public function getToken(): Hash;

    public function getProvider(): string;

    public function getUid(): string;

    public function haveExpired(\DateTimeImmutable $time): bool;

    public function isValid(string $token): bool;

    public function setToken(Hash $token): void;

    public function getAccountId(): int;

    public function setExpire(\DateTimeImmutable $time): AccountTokenInterface;

    public function __toString();
}