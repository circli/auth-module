<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Input;

use Circli\Middlewares\RequestAttributeKeys;
use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Authentication\Data\PasswordLoginData;
use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\EmailAddress;
use Psr\Http\Message\ServerRequestInterface;

final class PasswordLoginInput
{
    public function __invoke(ServerRequestInterface $request): LoginDataInterface
    {
        $body = $request->getParsedBody();
        $userAgent = $request->getAttribute(RequestAttributeKeys::USER_AGENT, '');
        $ip = $request->getAttribute(RequestAttributeKeys::IP) ?? '127.0.0.1';

        if (!isset($body['email'], $body['password']) || empty($body['email']) || empty($body['password'])) {
            throw new \DomainException('Missing data');
        }

        return new PasswordLoginData(
            EmailAddress::fromEmailAddress($body['email']),
            ClearTextPassword::fromInputPassword($body['password']),
            $ip,
            $userAgent,
            (bool)($body['remember'] ?? false)
        );
    }
}
