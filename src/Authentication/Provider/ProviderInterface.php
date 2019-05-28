<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Provider;

use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

interface ProviderInterface
{
    public function verify(LoginDataInterface $data): AccountInterface;
}