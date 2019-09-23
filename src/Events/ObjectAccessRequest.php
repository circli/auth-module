<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

final class ObjectAccessRequest extends AbstractAccessRequest implements OwnerAccessRequestInterface
{
    /** @var string */
    private $key;
    /** @var object */
    private $object;

    public function __construct(string $key, object $object)
    {
        $this->key = $key;
        $this->object = $object;
    }

    public function getDocument(): object
    {
        return $this->object;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
