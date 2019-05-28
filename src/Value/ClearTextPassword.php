<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Value;

use Circli\Modules\Auth\Authentication\PasswordHasher;
use Webmozart\Assert\Assert;

final class ClearTextPassword
{
    /** @var string*/
    private $clearTextPassword;

    private function __construct(string $clearTextPassword)
    {
        $this->clearTextPassword = $clearTextPassword;
    }

    public static function fromInputPassword(string $password) : self
    {
        Assert::notEmpty($password);
        return new self($password);
    }

    public function makeHash(PasswordHasher $hasher) : Hash
    {
        $yesThisIsAHashReally = $hasher->hash($this->clearTextPassword);
        \assert(\is_string($yesThisIsAHashReally));
        return Hash::fromHash($yesThisIsAHashReally);
    }

    public function verify(Hash $hash, PasswordHasher $hasher): bool
    {
        return $hasher->verify($hash, $this->clearTextPassword);
    }
}
