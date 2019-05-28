<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\Objects\Factory;

use Atlas\Mapper\Record;

interface ObjectFactoryInterface
{
    public function createCollection(iterable $recordSet): iterable;
    public function createObject(Record $record);
    public function getRecord($object): ?Record;
}