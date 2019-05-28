<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

final class OwnerAccessRequest extends AbstractAccessRequest
{
    /** @var object */
    private $document;

    public function __construct(object $document)
    {
        $this->document = $document;
    }

    public function getDocument(): object
    {
        return $this->document;
    }
}
