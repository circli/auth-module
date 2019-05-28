<?php
declare(strict_types=1);

namespace Circli\Modules\Auth\DataSource\AccountValues;

use Atlas\Mapper\Record;

/**
 * @method AccountValuesRow getRow()
 */
class AccountValuesRecord extends Record
{
    use AccountValuesFields;
}
