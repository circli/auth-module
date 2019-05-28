<?php

namespace Circli\Modules\Auth\DataSource\AccountToken;

use Atlas\Mapper\Record;
use Circli\Modules\Auth\Repositories\Objects\AccountTokenInterface;
use SplObjectStorage;

interface AccountTokenMapperInterface
{
    public function getTableName(): string;

    /**
     * @param Record|AccountTokenInterface $record
     * @param SplObjectStorage|null $tracker
     */
    public function persist(
        Record $record,
        SplObjectStorage $tracker = null
    ) : void;
}