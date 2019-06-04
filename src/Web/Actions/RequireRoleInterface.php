<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

interface RequireRoleInterface
{
    public function getRole(): string;
}