<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Actions;

use Circli\Modules\Auth\Web\Domain\CreateAccount;
use Circli\Modules\Auth\Web\Input\CreateAccountInput;
use Circli\WebCore\Common\Actions\AbstractAction;

final class CreateAccountAction extends AbstractAction
{
    protected $input = CreateAccountInput::class;
    protected $domain = CreateAccount::class;
}
