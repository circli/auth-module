<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Domain\Logout;
use Circli\Modules\Auth\Web\Input\LogoutInput;
use Circli\Modules\Auth\Web\Responder\LogoutResponder;
use Circli\WebCore\Common\Actions\AbstractAction;

final class HandleLogoutAction extends AbstractAction implements RequireAuthInterface
{
    protected $domain= Logout::class;
    protected $input= LogoutInput::class;
    protected $responder = LogoutResponder::class;
}
