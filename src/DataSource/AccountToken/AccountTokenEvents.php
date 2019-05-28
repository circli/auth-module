<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountToken;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperEvents;
use Atlas\Mapper\Record;
use Atlas\Query\Delete;
use Atlas\Query\Insert;
use Atlas\Query\Update;
use PDOStatement;

class AccountTokenEvents extends MapperEvents
{
}
