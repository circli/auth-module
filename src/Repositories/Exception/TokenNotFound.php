<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Exception;

use Circli\Modules\Auth\Authentication\Exception\AuthenticateModuleException;

final class TokenNotFound extends \DomainException implements AuthenticateModuleException
{

}