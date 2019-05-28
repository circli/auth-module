<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication;

interface TokenProviders
{
    public const PASSWORD = 'password';
    public const SESSION = 'session';
    public const ONE_TIME = 'one-time';
    public const ACTIVATION = 'activation';
    public const INVITATION = 'invite';
    public const REMEMBER_ME = 'remember-me';

    public const ALL = [
        self::PASSWORD,
        self::SESSION,
        self::ONE_TIME,
        self::ACTIVATION,
        self::INVITATION,
        self::REMEMBER_ME
    ];
}