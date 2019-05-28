<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Exception;

final class NotAuthenticated extends \DomainException implements AuthenticateModuleException
{
    public const EXPIRED = 0x10;
    public const INVALID = 0x20;

    /** @var int */
    private $accountId;

    public function __construct(string $message = "", int $accountId = 0, $code = 0)
    {
        parent::__construct($message, $code);
        $this->accountId = $accountId;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }
}
