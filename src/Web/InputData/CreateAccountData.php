<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\InputData;

use Circli\Modules\Auth\Repositories\Enums\Status;
use Circli\Modules\Auth\Repositories\Objects\RoleInterface;
use Circli\Modules\Auth\Repositories\Objects\Value;

final class CreateAccountData
{
    /** @var Status */
    private $status;
    /** @var RoleInterface[] */
    private $roles = [];
    /** @var Value[] */
    private $values = [];

    private $authMethod;

    public function __construct(Status $status, array $values, array $roles, ?AuthMethodData $authMethod)
    {
        $this->status = $status;
        foreach ($values as $key => $value) {
            if (!is_string($key)) {
                continue;
            }
            $this->values[] = new Value($key, $value, true);
        }
        foreach ($roles as $role) {
            if ($role instanceof RoleInterface) {
                $this->roles[] = $role;
            }
        }
        $this->authMethod = $authMethod;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return RoleInterface[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return Value[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return AuthMethodData
     */
    public function getAuthMethod(): AuthMethodData
    {
        return $this->authMethod;
    }
}
