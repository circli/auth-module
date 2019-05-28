<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AclRoleAcces;

use Atlas\Mapper\Record;

/**
 * @method AclRoleAccesRow getRow()
 */
class AclRoleAccesRecord extends Record
{
    use AclRoleAccesFields;
}
