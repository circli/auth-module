<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Payload;

use Aura\Payload\Payload;

class LogoutPayload extends Payload
{
    public const SUCCESS = 'success';
    protected const ALLOWED_STATUS = [self::SUCCESS];
    protected const MESSAGES = [
        self::SUCCESS => 'Logout successful',
    ];

    public function __construct(string $status)
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
    }
}
