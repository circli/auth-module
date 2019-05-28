<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Responder;

use Circli\Extensions\Template\LayoutTemplateResponder;

class ViewLoginResponder extends LayoutTemplateResponder
{
    protected $templateFile = 'auth:login';
}
