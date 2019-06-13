<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Responder\ViewLoginResponder;
use Circli\WebCore\Common\Actions\AbstractAction;

final class ViewLoginAction extends AbstractAction implements ViewLoginInterface
{
    protected $responder = ViewLoginResponder::class;
}
