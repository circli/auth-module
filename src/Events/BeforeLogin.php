<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Authentication\AuthResponseStatus;
use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;

final class BeforeLogin
{
    /** @var LoginDataInterface */
    private $data;
    /** @var AuthResponseStatus|null */
    private $authResponseCode;

    public function __construct(LoginDataInterface $data)
    {
        $this->data = $data;
    }

    public function getData(): LoginDataInterface
    {
        return $this->data;
    }

    public function setAuthResponseCode(AuthResponseStatus $code): void
    {
        $this->authResponseCode = $code;
    }

    public function getAuthResponseCode(): ?AuthResponseStatus
    {
        return $this->authResponseCode;
    }
}
