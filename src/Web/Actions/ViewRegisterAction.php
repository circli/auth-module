<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Responder\ViewCreateAccountResponder;
use Circli\WebCore\Common\Actions\AbstractAction;

final class ViewRegisterAction extends AbstractAction implements RequireAuthInterface
{
    protected $responder = ViewCreateAccountResponder::class;
}