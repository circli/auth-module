<?php declare(strict_types=1);

namespace Circli\Modules\Auth;

use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;

interface AuthSessionInterface
{
    public function destroy(): bool;

    public function persist(AccountTokenInterface $token): bool;

    public function getSessionData(): array;
}
