<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Responder\LogoutResponder;
use Circli\WebCore\Common\Actions\AbstractAction;

final class HandleLogoutAction extends AbstractAction implements RequireAuthInterface
{
    protected $responder = LogoutResponder::class;
}
