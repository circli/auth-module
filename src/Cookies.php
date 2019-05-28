<?php declare(strict_types=1);

namespace Circli\Modules\Auth;

interface Cookies
{
    public const REMEMBER_ME = 'circli|auth|remember_me';
    public const SESSION = 'circli|auth|session';
}