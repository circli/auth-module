<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Data;

interface TokenLoginDataInterface extends LoginDataInterface
{
    public function getTokenProvider(): string;

    public function getTokenUid(): string;

    public function getToken(): string;
}