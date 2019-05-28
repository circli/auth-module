<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Web\InputData;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;

final class LogoutData
{
    /** @var AccountTokenInterface|null */
    private $sessionToken;
    /** @var AccountInterface */
    private $account;

    public function __construct(AccountInterface $account, ?AccountTokenInterface $sessionToken)
    {
        $this->account = $account;
        $this->sessionToken = $sessionToken;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function getSessionToken(): ?AccountTokenInterface
    {
        return $this->sessionToken;
    }
}
