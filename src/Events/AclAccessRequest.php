<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

final class AclAccessRequest extends AbstractAccessRequest
{
    /** @var string */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
