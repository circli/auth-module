<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication;

use Sunkan\Enum\Enum;

/**
 * @method static SUCCESS(): static
 * @method static NOT_FOUND(): static
 * @method static FAIL(): static
 */
final class AuthResponseStatus extends Enum
{
    private const SUCCESS = 'success';
    private const NOT_FOUND = 'not_found';
    private const FAIL = 'fail';
}