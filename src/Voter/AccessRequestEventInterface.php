<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Psr\EventDispatcher\StoppableEventInterface;

interface AccessRequestEventInterface extends StoppableEventInterface
{
    public function allow(): void;

    public function deny(): void;

    public function allowed(): bool;
}
