<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects;

final class StaticRole implements RoleInterface
{
    /** @var int */
    private $roleId;

    public static function fromId(int $id): self
    {
        return new self($id);
    }

    private function __construct(int $roleId)
    {
        $this->roleId = $roleId;
    }

    public function getId(): int
    {
        return $this->roleId;
    }
}