<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

final class GuestAcl extends Acl
{
    public function __construct()
    {
        parent::__construct(new GuestAccount(), [], []);
    }
}