<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Exception;

use Circli\Modules\Auth\Authentication\Exception\AuthenticateModuleException;

final class AccountNotFound extends \DomainException implements AuthenticateModuleException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}