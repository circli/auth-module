<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Circli\Modules\Auth\Events\RouteAccessRequest;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;
use Circli\Modules\Auth\Web\Actions\RequireRoleInterface;

class RequireRoleActionVoter implements VoterInterface
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
            if (is_object($action) && $action instanceof RequireRoleInterface) {
                if ($this->account->haveRole($action->getRole())) {
                    $accessRequestEvent->allow();
                }
                else {
                    $accessRequestEvent->deny();
                }
            }
        }
    }
}
