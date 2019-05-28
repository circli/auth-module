<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Authentication\Exception\AuthenticateModuleException;

final class LoginError
{
    /** @var AuthenticateModuleException */
    private $exception;

    public function __construct(AuthenticateModuleException $exception)
    {
        $this->exception = $exception;
    }

    public function getException(): AuthenticateModuleException
    {
        return $this->exception;
    }
}