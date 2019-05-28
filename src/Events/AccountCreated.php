<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class AccountCreated
{
    /** @var AccountInterface */
    private $account;

    public function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }
}