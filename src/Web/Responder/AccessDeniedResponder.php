<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Responder;

use Circli\Extensions\Template\Responder\LayoutTemplateResponder;

class AccessDeniedResponder extends LayoutTemplateResponder
{
    protected $templateFile = 'auth:denied.php';
}