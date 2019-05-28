<?php

namespace Circli\Modules\Auth\DataSource\AccountLoginLog;

interface LoginLogMapperInterface
{
    public function createNewRecord(
        int $accountId,
        \DateTimeImmutable $loginTime,
        string $ip,
        string $ua,
        string $provider,
        string $status
    ): bool;
}