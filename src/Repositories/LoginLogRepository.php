<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories;

use Circli\Extensions\Encryption\FieldEncrypterAwareInterface;
use Circli\Extensions\Encryption\FieldEncrypterFactoryInterface;
use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\DataSource\AccountLoginLog\LoginLogMapperInterface;

final class LoginLogRepository implements LoginLogRepositoryInterface
{
    /** @var LoginLogMapperInterface */
    private $mapper;

    public function __construct(LoginLogMapperInterface $mapper, FieldEncrypterFactoryInterface $fieldEncrypterFactory)
    {
        $this->mapper = $mapper;
        if ($this->mapper instanceof FieldEncrypterAwareInterface) {
            $this->mapper->setFieldEncrypterFactory($fieldEncrypterFactory);
        }
    }

    public function addLoginAttempt(int $accountId, LoginDataInterface $info, string $status): void
    {
        $this->mapper->createNewRecord(
            $accountId,
            new \DateTimeImmutable('now'),
            $info->getIp(),
            $info->getUserAgent(),
            $info->getProvider(),
            $status
        );
    }
}