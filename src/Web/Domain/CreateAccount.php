<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Domain;

use Circli\Modules\Auth\Authentication\Web\InputData\CreateAccountData;
use Circli\Modules\Auth\Authentication\Web\Payload\CreateAccountPayload;
use Circli\Modules\Auth\Events\AccountCreated;
use Circli\Modules\Auth\Repositories\AccessRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class CreateAccount
{
    /** @var AccountTokenRepositoryInterface */
    private $accountTokenRepository;
    /** @var LoggerInterface */
    private $logger;
    /** @var AccountRepositoryInterface */
    private $accountRepository;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var AccessRepositoryInterface */
    private $accessRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        AccountTokenRepositoryInterface $accountTokenRepository,
        AccessRepositoryInterface $accessRepository,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->accountRepository = $accountRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->accessRepository = $accessRepository;
        $this->accountTokenRepository = $accountTokenRepository;
        $this->logger = $logger;
    }

    public function __invoke(CreateAccountData $data)
    {
        $secret = bin2hex(random_bytes(32));

        try {
            //todo add transaction
            $account = $this->accountRepository->create($secret, $data->getStatus());
            foreach ($data->getValues() as $value) {
                $this->accountRepository->addAccountValue($account, $value);
            }

            if ($data->getAuthMethod()) {
                $this->accountTokenRepository->createFromAuthData($account, $data->getAuthMethod());
            }

            foreach ($data->getRoles() as $role) {
                $this->accessRepository->addAccountRole($account, $role);
            }

            $account = $this->accountRepository->refreshById($account->getId());

            $this->eventDispatcher->dispatch(new AccountCreated($account));

            $this->logger->info('Account created', [
                'account' => $account->getId(),
            ]);

            return new CreateAccountPayload(CreateAccountPayload::SUCCESS, $account);
        }
        catch (\Exception $e) {
            $this->logger->error('Error create new account', [
                'exception' => $e,
                'authData' => $data->getAuthMethod()->getAuthData(),
            ]);
            return new CreateAccountPayload(CreateAccountPayload::ERROR);
        }
    }
}
