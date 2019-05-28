<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication;

use Circli\Modules\Auth\Value\Hash;

final class PasswordHasher
{
    private $hashOptions;

    /**
     * PasswordHasher constructor.
     * @see http://php.net/manual/en/password.constants.php for $hashOptions
     * @param array $hashOptions
     */
    public function __construct(array $hashOptions)
    {
        $this->hashOptions = $hashOptions;
    }

    /**
     * Hash password
     *
     * @param string $password
     * @return string
     */
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT, $this->hashOptions);
    }

    /**
     * Verify if password is valid for account
     *
     * @param Hash $token
     * @param string $password
     * @return bool
     */
    public function verify(Hash $token, string $password): bool
    {
        return password_verify($password, $token->toString());
    }

    /**
     * Check if account password needs to be rehashed
     *
     * @param Hash $token
     * @return bool
     */
    public function needsRehash(Hash $token): bool
    {
        return password_needs_rehash($token->toString(), PASSWORD_DEFAULT, $this->hashOptions);
    }
}