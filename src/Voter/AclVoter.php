<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Circli\Modules\Auth\Events\AclAccessRequest;
use Circli\Modules\Auth\Events\OwnerAccessRequest;
use Circli\Modules\Auth\Events\OwnerAccessRequestInterface;
use Circli\Modules\Auth\Repositories\AccessRepositoryInterface;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class AclVoter implements VoterInterface
{
    /** @var AccessRepositoryInterface */
    private $accessRepository;
    /** @var AccountInterface */
    private $account;

    public function __construct(AccessRepositoryInterface $accessRepository)
    {
        $this->accessRepository = $accessRepository;
    }

    public function setAccount(AccountInterface $account): void
    {
        $this->account = $account;
    }

    public function supports(AccessRequestEventInterface $accessRequest): bool
    {
        return $accessRequest instanceof AclAccessRequest || $accessRequest instanceof OwnerAccessRequestInterface;
    }

    /**
     * @param AccessRequestEventInterface|AclAccessRequest $accessRequestEvent
     */
    public function __invoke(AccessRequestEventInterface $accessRequestEvent): void
    {
        $acl = $this->accessRepository->buildAcl($this->account);
        if ($accessRequestEvent instanceof AclAccessRequest) {
            if ($acl->isAllowed($accessRequestEvent->getKey())) {
                $accessRequestEvent->allow();
            }
        }
        elseif ($accessRequestEvent instanceof OwnerAccessRequestInterface) {
            if ($acl->isOwner($accessRequestEvent->getDocument())) {
                $accessRequestEvent->allow();
            }
        }
    }
}
