<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

interface AclInterface
{
    public function getAccount(): AccountInterface;

    public function isAllowed(string $key): bool;

    public function isOwner(object $obj): bool;
}
