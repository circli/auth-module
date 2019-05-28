<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web;

interface RequestAttributeKeys
{
    public const ACCOUNT = 'circli:account';
    public const AUTH = 'circli:auth';
    public const SESSION_TOKEN = 'circli:auth:session_token';
}