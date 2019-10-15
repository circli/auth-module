<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Enums;

use Sunkan\Enum\Enum;

/**
 * @method static Status APPROVED()
 * @method static Status PENDING()
 * @method static Status BLOCKED()
 * @method static Status INVITED()
 * @method static Status TEMPORARY()
 * @method static Status DELETED()
 */
final class Status extends Enum
{
    public const APPROVED = 'approved';
    public const PENDING = 'pending';
    public const BLOCKED = 'blocked';
    public const INVITED = 'invited';
    public const TEMPORARY = 'temporary';
    public const DELETED = 'deleted';
}
