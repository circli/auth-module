<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Enums;

use Sunkan\Enum\Enum;

/**
 * @method static self APPROVED()
 * @method static self PENDING()
 * @method static self BLOCKED()
 * @method static self INVITED()
 * @method static self TEMPORARY()
 * @method static self DELETED()
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
