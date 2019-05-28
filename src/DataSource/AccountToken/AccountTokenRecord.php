<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountToken;

use Atlas\Mapper\Record;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;
use Circli\Modules\Auth\Value\Hash;

/**
 * @method AccountTokenRow getRow()
 */
class AccountTokenRecord extends Record implements AccountTokenInterface
{
    private $expireCache;

    use AccountTokenFields;

    public function getToken(): Hash
    {
        return Hash::fromHash($this->token);
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getExpire(): ?\DateTimeImmutable
    {
        if (!$this->expire) {
            return null;
        }
        if (!$this->expireCache) {
            $this->expireCache = new \DateTimeImmutable($this->expire);
        }

        return $this->expireCache;
    }

    public function haveExpired(\DateTimeImmutable $time): bool
    {
        $expire = $this->getExpire();
        if ($expire === null) {
            return false;
        }

        return $time > $expire;
    }

    public function isValid(string $token): bool
    {
        return hash_equals($this->token, $token);
    }

    public function setToken(Hash $token): void
    {
        $this->token = $token->toString();
    }

    public function getAccountId(): int
    {
        return (int) $this->account_id;
    }

    public function setExpire(\DateTimeImmutable $expire): AccountTokenInterface
    {
        $this->expire = $expire->format('Y-m-d H:i:s');
        $this->expireCache = $expire;

        return $this;
    }

    public function __toString()
    {
        return $this->uid . '.' . $this->token;
    }
}
