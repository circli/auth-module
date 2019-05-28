<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Session;

use Psr\Http\Message\ServerRequestInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

final class DefaultFactory implements Factory
{
    public function fromRequest(ServerRequestInterface $request): AuthSessionInterface
    {
        if ($request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE)) {
            return new StoragelessSession($request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE));
        }

        return new PhpSession();
    }
}