<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountRole;

use Atlas\Mapper\Record;

/**
 * @method AccountRoleRow getRow()
 */
class AccountRoleRecord extends Record
{
    use AccountRoleFields;
}
