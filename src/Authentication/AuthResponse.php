<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;

final class AuthResponse
{
    /** @var AuthResponseStatus */
    private $status;
    /** @var AccountInterface|null */
    private $account;
    /** @var AccountTokenInterface */
    private $sessionToken;

    public function __construct(
        AuthResponseStatus $status,
        AccountInterface $account = null,
        AccountTokenInterface $sessionToken = null
    ) {
        $this->status = $status;
        $this->account = $account;
        $this->sessionToken = $sessionToken;
    }

    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    public function getStatus(): AuthResponseStatus
    {
        return $this->status;
    }

    public function getSessionToken(): AccountTokenInterface
    {
        return $this->sessionToken;
    }
}
