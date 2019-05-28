<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Data;

use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\EmailAddress;

interface PasswordLoginDataInterface extends LoginDataInterface
{
    public function getEmail(): EmailAddress;
    public function getPassword(): ClearTextPassword;
    public function rememberMe(): bool;
}