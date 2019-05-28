<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Data;

use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\EmailAddress;

final class PasswordLoginData implements PasswordLoginDataInterface
{
    /** @var bool */
    private $remember;
    /** @var EmailAddress */
    private $email;
    /** @var string */
    private $password;
    /** @var string */
    private $ip;
    /** @var string */
    private $userAgent;

    public function __construct(EmailAddress $email, ClearTextPassword $password, string $ip, string $userAgent, bool $remember)
    {
        $this->email = $email;
        $this->password = $password;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->remember = $remember;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getPassword(): ClearTextPassword
    {
        return $this->password;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getProvider(): string
    {
        return 'password';
    }

    public function rememberMe(): bool
    {
        return $this->remember;
    }
}