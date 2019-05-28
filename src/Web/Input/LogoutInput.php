<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Input;

use Circli\Modules\Auth\Authentication\Web\InputData\LogoutData;
use Circli\Modules\Auth\Web\RequestAttributeKeys;
use Psr\Http\Message\ServerRequestInterface;

final class LogoutInput
{
    public function __invoke(ServerRequestInterface $request)
    {
        $account = $request->getAttribute(RequestAttributeKeys::ACCOUNT);
        $sessionToken = $request->getAttribute(RequestAttributeKeys::SESSION_TOKEN);

        return new LogoutData($account, $sessionToken);
    }
}
