<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Web\Payload;

use Aura\Payload\Payload;
use Circli\Modules\Auth\Authentication\AuthResponse;

class LoginPayload extends Payload
{
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    protected const ALLOWED_STATUS = [self::SUCCESS, self::ERROR];
    protected const MESSAGES = [
        self::SUCCESS => 'Login success',
        self::ERROR => 'Login failed'
    ];

    public function __construct(string $status, AuthResponse $authResponse = null)
    {
        if (!\in_array($status, static::ALLOWED_STATUS, true)) {
            throw new \InvalidArgumentException('Invalid payload status');
        }
        if (static::MESSAGES[$status]) {
            $message = static::MESSAGES[$status];
        }
        else {
            $message = $status;
        }

        $this->status = $status;
        $this->messages = $message;
        $this->output = new \stdClass;
        $this->output->authResponse = $authResponse;
    }

    public function getAuthResponse(): ?AuthResponse
    {
        return $this->output->authResponse;
    }
}
