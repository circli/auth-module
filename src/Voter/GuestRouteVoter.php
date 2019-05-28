<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Circli\Modules\Auth\Events\RouteAccessRequest;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class GuestRouteVoter implements VoterInterface
{
    /** @var AccountInterface */
    private $account;

    public function setAccount(AccountInterface $account): void
    {
        $this->account = $account;
    }

    public function supports(AccessRequestEventInterface $accessRequest): bool
    {
        return $accessRequest instanceof RouteAccessRequest;
    }

    public function __invoke(AccessRequestEventInterface $accessRequestEvent): void
    {
        if (($accessRequestEvent instanceof RouteAccessRequest) && !$accessRequestEvent->getAuth()->isAuthenticated()) {
            $accessRequestEvent->allow();
        }
    }
}
