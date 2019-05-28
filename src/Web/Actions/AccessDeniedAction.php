<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Responder\AccessDeniedResponder;
use Circli\WebCore\Common\Actions\AbstractAction;

final class AccessDeniedAction extends AbstractAction
{
    protected $responder = AccessDeniedResponder::class;
}