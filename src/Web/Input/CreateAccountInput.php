<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Input;

use Circli\Modules\Auth\Authentication\TokenProviders;
use Circli\Modules\Auth\Web\InputData\AuthMethodData;
use Circli\Modules\Auth\Web\InputData\CreateAccountData;
use Circli\Modules\Auth\Repositories\Enums\Status;
use Circli\Modules\Auth\Repositories\Objects\StaticRole;
use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\EmailAddress;
use Circli\Modules\Auth\Web\InputData\PasswordProviderData;
use Psr\Http\Message\ServerRequestInterface;

final class CreateAccountInput
{
    public function __invoke(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        $email = EmailAddress::fromEmailAddress($body['email']);
        $password = ClearTextPassword::fromInputPassword($body['password']);

        //todo inject CreateAccountDefaults

        return new CreateAccountData(
            Status::APPROVED(),
            [
                'name' => $body['name'],
            ],
            [
                StaticRole::fromId(1),
            ],
            new AuthMethodData(
                TokenProviders::PASSWORD,
                new PasswordProviderData($email, $password)
            )
        );
    }
}
