<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Atlas\Mapper\Record;
use Circli\Extensions\Encryption\FieldEncrypterFactoryInterface;
use Circli\Modules\Auth\Authentication\PasswordHasher;
use Circli\Modules\Auth\Authentication\TokenProviders;
use Circli\Modules\Auth\Authentication\Web\InputData\AuthMethodData;
use Circli\Modules\Auth\DataSource\AccountToken\AccountToken;
use Circli\Modules\Auth\DataSource\AccountToken\AccountTokenMapperInterface;
use Circli\Modules\Auth\Repositories\EncryptionFields\TokenUidField;
use Circli\Modules\Auth\Repositories\Exception\TokenNotFound;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;
use Circli\Modules\Auth\Value\EmailAddress;
use Circli\Modules\Auth\Web\InputData\PasswordProviderData;

final class AccountTokenRepository implements AccountTokenRepositoryInterface
{
    /** @var AccountTokenMapperInterface|AccountToken */
    private $mapper;
    /** @var FieldEncrypterFactoryInterface */
    private $fieldEncrypterFactory;
    /** @var PasswordHasher */
    private $hasher;

    public function __construct(
        AccountTokenMapperInterface $mapper,
        FieldEncrypterFactoryInterface $fieldEncrypterFactory,
        PasswordHasher $hasher
    ) {
        $this->mapper = $mapper;
        $this->fieldEncrypterFactory = $fieldEncrypterFactory;
        $this->hasher = $hasher;
    }

    /**
     * @param string $provider
     * @param string $uid
     * @return AccountTokenInterface[]
     */
    public function findByProviderAndUid(string $provider, string $uid)
    {
        $tokens = $this->mapper->select()
            ->where('uid = ', $uid)
            ->where('provider = ', $provider)
            ->fetchRecords();

        return $tokens;
    }

    /**
     * @param AccountInterface $account
     * @return AccountTokenInterface[]
     */
    public function findByAccount(AccountInterface $account): array
    {
        return $this->mapper->select()
            ->where('account_id = ', $account->getId())
            ->fetchRecords();
    }

    public function findByAccountAndProvider(AccountInterface $account, string $provider): AccountTokenInterface
    {
        $token = $this->mapper->select()
            ->where('account_id = ', $account->getId())
            ->where('provider = ', $provider)
            ->fetchRecord();

        if (!$token) {
            throw new TokenNotFound("Account don't have a token of type '$provider'");
        }

        return $token;
    }

    public function delete(AccountTokenInterface $accountToken): bool
    {
        if ($accountToken instanceof Record) {
            $this->mapper->delete($accountToken);
        }
        return true;
    }

    public function save(AccountTokenInterface $accountToken): AccountTokenInterface
    {
        $this->mapper->persist($accountToken);
        return $accountToken;
    }

    public function getHashIndex(EmailAddress $value): string
    {
        return $this->fieldEncrypterFactory
            ->getFieldEncrypter(TokenUidField::class)
            ->getBlindIndex($value->toString());
    }

    public function create(
        AccountInterface $account,
        string $provider,
        ?\DateTimeImmutable $expire
    ): AccountTokenInterface {
        $token = $this->mapper->newRecord();
        $token->account_id = $account->getId();
        $token->provider = $provider;

        if ($expire) {
            $token->expire = $expire->format('Y-m-d H:i:s');
        }

        $token->uid = bin2hex(random_bytes(20));
        $token->token = bin2hex(random_bytes(20));
        $token->created = date('Y-m-d H:i:s');

        $this->mapper->insert($token);

        return $token;
    }

    public function createFromAuthData(AccountInterface $account, AuthMethodData $data): AccountTokenInterface
    {
        $token = $this->mapper->newRecord();
        $token->account_id = $account->getId();
        $token->provider = $data->getProvider();
        $token->created = date('Y-m-d H:i:s');

        $authData = $data->getAuthData();
        if ($token->provider === TokenProviders::PASSWORD && $authData instanceof PasswordProviderData) {
            $token->uid = $this->getHashIndex($authData->getEmailAddress());
            $token->token = $authData->getPassword()->makeHash($this->hasher)->toString();
        }
        else {
            $token->uid = bin2hex(random_bytes(20));
            $token->token = bin2hex(random_bytes(20));
        }

        $this->mapper->insert($token);

        return $token;
    }
}