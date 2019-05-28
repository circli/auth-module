<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Authentication\AuthResponse;
use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;

final class PostLogin
{
    /** @var AuthResponse */
    private $authResponse;
    /** @var LoginDataInterface */
    private $data;

    public function __construct(AuthResponse $authResponse, LoginDataInterface $data)
    {
        $this->authResponse = $authResponse;
        $this->data = $data;
    }

    public function getAuthResponse(): AuthResponse
    {
        return $this->authResponse;
    }

    public function getData(): LoginDataInterface
    {
        return $this->data;
    }
}
