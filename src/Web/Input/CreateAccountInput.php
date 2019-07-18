<?php

namespace Circli\Modules\Auth\Web\Input;

use Circli\Modules\Auth\Web\InputData\CreateAccountData;
use Psr\Http\Message\ServerRequestInterface;

interface CreateAccountInput
{
    public function __invoke(ServerRequestInterface $request): CreateAccountData;
}