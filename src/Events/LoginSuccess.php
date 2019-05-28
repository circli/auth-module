<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class LoginSuccess
{
    /** @var LoginDataInterface */
    private $loginData;
    /** @var AccountInterface */
    private $account;

    public function __construct(AccountInterface $account, LoginDataInterface $loginData)
    {
        $this->account = $account;
        $this->loginData = $loginData;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function getLoginData(): LoginDataInterface
    {
        return $this->loginData;
    }
}
