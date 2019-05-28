<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class AccessDenied
{
    public const OWNER = 'owner';
    public const PERMISSION = 'permission';

    /** @var AccountInterface */
    private $account;
    /** @var string */
    private $type;
    /** @var string */
    private $value;

    public function __construct(AccountInterface $account, string $type, string $value)
    {
        $this->account = $account;
        $this->type = $type;
        $this->value = $value;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
