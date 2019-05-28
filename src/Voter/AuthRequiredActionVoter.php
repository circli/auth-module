<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Circli\Modules\Auth\Events\RouteAccessRequest;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class AuthRequiredActionVoter implements VoterInterface
{
    /** @var AccountInterface */
    private $account;

    private $actions = [];

    public function setAccount(AccountInterface $account): void
    {
        $this->account = $account;
    }

    public function addAction(string $action): void
    {
        $this->actions[] = $action;
    }

    public function supports(AccessRequestEventInterface $accessRequest): bool
    {
        return $accessRequest instanceof RouteAccessRequest;
    }

    public function __invoke(AccessRequestEventInterface $accessRequestEvent): void
    {
        if ($accessRequestEvent instanceof RouteAccessRequest) {
            $action = $accessRequestEvent->getRoute()->getHandler();
            if (is_object($action)) {
                foreach ($this->actions as $testAction) {
                    if ($action instanceof $testAction) {
                        if (!$accessRequestEvent->getAuth()->isAuthenticated()) {
                            $accessRequestEvent->deny();
                        }
                        else {
                            $accessRequestEvent->allow();
                        }
                        return;
                    }
                }
            }
        }
    }
}