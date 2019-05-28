<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Domain\Login;
use Circli\Modules\Auth\Web\Input\PasswordLoginInput;
use Circli\Modules\Auth\Web\Responder\LoginResponder;
use Circli\WebCore\Common\Actions\AbstractAction;

final class HandleLoginAction extends AbstractAction
{
    protected $input = PasswordLoginInput::class;
    protected $domain = Login::class;
    protected $responder = LoginResponder::class;
}