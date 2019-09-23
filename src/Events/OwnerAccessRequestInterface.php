<?php

namespace Circli\Modules\Auth\Events;

interface OwnerAccessRequestInterface
{
    public function getDocument(): object;
}