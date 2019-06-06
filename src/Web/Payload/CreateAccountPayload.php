<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Payload;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

class CreateAccountPayload extends Payload
{
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const ALREADY_CREATED = PayloadStatus::FOUND;

    protected const ALLOWED_STATUS = [self::SUCCESS, self::ERROR, self::ALREADY_CREATED];
    protected const MESSAGES = [
        self::SUCCESS => 'Account created',
        self::ERROR => 'Failed to create account',
        self::ALREADY_CREATED => 'Failed to create account. Details already in use.'
    ];

    public function __construct(string $status, AccountInterface $account = null)
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
        $this->output->account = $account;
    }

    public function getAccount(): ?AccountInterface
    {
        return $this->output->account;
    }
}
