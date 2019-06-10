<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Session;

use Psr\Http\Message\ServerRequestInterface;

interface Factory
{
    public function fromRequest(ServerRequestInterface $request): AuthSessionInterface;
}
