<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AclRole;

use Atlas\Mapper\Record;
use Circli\Modules\Auth\Repositories\Objects\RoleInterface;

/**
 * @method AclRoleRow getRow()
 */
class AclRoleRecord extends Record implements RoleInterface
{
    use AclRoleFields;

    public function getId(): int
    {
        return $this->id;
    }
}
