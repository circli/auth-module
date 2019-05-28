<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Voter;

use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

interface VoterInterface
{
    public function setAccount(AccountInterface $account): void;
    
    public function supports(AccessRequestEventInterface $accessRequest): bool;

    public function __invoke(AccessRequestEventInterface $accessRequestEvent): void;
}