<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Voter\AccessRequestEventInterface;

class AbstractAccessRequest implements AccessRequestEventInterface
{
    /** @var bool|null */
    protected $allow;

    public function allow(): void
    {
        $this->allow = true;
    }

    public function deny(): void
    {
        $this->allow = false;
    }

    public function isPropagationStopped(): bool
    {
        return $this->allow === false;
    }

    public function allowed(): bool
    {
        return $this->allow === true;
    }
}