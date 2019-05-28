<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\EncryptionFields;

use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\EncryptedField;

final class AccountValueField extends EncryptedField
{
    public function __construct(CipherSweet $engine)
    {
        parent::__construct($engine, 'account_values', 'value_data');
        $this->addBlindIndex(new BlindIndex('value_idx'));
    }
}
