<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;

interface LoginLogRepositoryInterface
{
    public function addLoginAttempt(int $accountId, LoginDataInterface $info, string $string);
}
