<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\Account;

use Atlas\Mapper\MapperRelationships;
use Circli\Modules\Auth\DataSource\AccountValues\AccountValues;

class AccountRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->oneToMany('values', AccountValues::class, [
            'id' => 'account_id',
        ]);
    }
}
