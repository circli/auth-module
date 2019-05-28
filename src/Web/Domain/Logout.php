<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Domain;

use Circli\Modules\Auth\Authentication\Web\InputData\LogoutData;
use Circli\Modules\Auth\Authentication\Web\Payload\LogoutPayload;
use Circli\Modules\Auth\Events\Logout as LogoutEvent;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Logout
{
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, AccountTokenRepositoryInterface $tokenRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenRepository = $tokenRepository;
    }

    public function __invoke(LogoutData $data)
    {
        $this->eventDispatcher->dispatch(new LogoutEvent($data->getAccount()));

        if ($data->getSessionToken()) {
            $this->tokenRepository->delete($data->getSessionToken());
        }

        return new LogoutPayload(LogoutPayload::SUCCESS);
    }
}
