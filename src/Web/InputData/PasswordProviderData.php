<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\InputData;

use Circli\Modules\Auth\Value\ClearTextPassword;
use Circli\Modules\Auth\Value\EmailAddress;

final class PasswordProviderData implements ProviderDataInterface
{
    /** @var EmailAddress */
    private $emailAddress;
    /** @var ClearTextPassword */
    private $password;

    public function __construct(EmailAddress $emailAddress, ClearTextPassword $password)
    {
        $this->emailAddress = $emailAddress;
        $this->password = $password;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function getPassword(): ClearTextPassword
    {
        return $this->password;
    }
}
