<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\EncryptionFields;

use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\EncryptedField;

final class TokenUidField extends EncryptedField
{
    public function __construct(CipherSweet $engine)
    {
        parent::__construct($engine, 'account_tokens', 'uid');
        $this->addBlindIndex(new BlindIndex('uid_idx'));
    }
}
