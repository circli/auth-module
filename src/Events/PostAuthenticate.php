<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

use Circli\Modules\Auth\Auth;
use Circli\Modules\Auth\Repositories\Objects\AccountInterface;

final class PostAuthenticate implements HaveTemplateDataInterface
{
    /** @var Auth */
    private $auth;
    /** @var AccountInterface */
    private $account;

    public function __construct(Auth $auth, AccountInterface $account)
    {
        $this->auth = $auth;
        $this->account = $account;
    }

    public function getTemplateData(): array
    {
        return [
            'currentAccount' => $this->account,
            'auth' => $this->auth,
        ];
    }
}