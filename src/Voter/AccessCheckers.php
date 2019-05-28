<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Repositories\Objects\GuestAccount;
use Psr\EventDispatcher\ListenerProviderInterface;

final class AccessCheckers implements ListenerProviderInterface
{
    /** @var VoterInterface[] */
    private $voters = [];
    /** @var AccountInterface|null */
    private $account;

    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }
    
    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        if ($event instanceof AccessRequestEventInterface) {
            $account = $this->account ?? new GuestAccount();
            foreach ($this->voters as $voter) {
                if ($voter->supports($event)) {
                    $voter->setAccount($account);
                    yield $voter;
                }
            }
        }
    }

    public function setAccount(AccountInterface $account): void
    {
        $this->account = $account;
    }
}
