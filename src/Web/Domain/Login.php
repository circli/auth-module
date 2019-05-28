<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Domain;

use Circli\Modules\Auth\Authentication\AuthResponseStatus;
use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Authentication\Handler;
use Circli\Modules\Auth\Authentication\Web\Payload\LoginPayload;

final class Login
{
    /** @var Handler */
    private $loginHandler;

    public function __construct(Handler $loginHandler)
    {
        $this->loginHandler = $loginHandler;
    }

    public function __invoke(LoginDataInterface $data)
    {
        $authResponse = $this->loginHandler->handle($data);

        usleep(100 + random_int(0, 50));

        if ($authResponse->getStatus()->is(AuthResponseStatus::SUCCESS())) {
            return new LoginPayload(LoginPayload::SUCCESS, $authResponse);
        }

        return new LoginPayload(LoginPayload::ERROR);
    }
}