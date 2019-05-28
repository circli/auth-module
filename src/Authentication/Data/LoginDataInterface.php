<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Data;

interface LoginDataInterface
{
    public function getProvider(): string;

    public function getIp(): string;

    public function getUserAgent(): string;
}
