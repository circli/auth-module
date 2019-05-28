<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Repositories\EncryptionFields;

use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\EncryptedField;

final class IpField extends EncryptedField
{
    public function __construct(CipherSweet $engine)
    {
        parent::__construct($engine, 'account_login_log', 'ip');
        $this->addBlindIndex(new BlindIndex('ip_idx'));
    }
}
