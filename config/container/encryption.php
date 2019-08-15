<?php

use Circli\Core\Config;
use Circli\Extensions\Encryption\Cookie\EncryptedCookieFactory;
use Circli\Modules\Auth\Web\Middleware\RememberMeMiddleware;
use ParagonIE\Halite\Cookie;
use ParagonIE\Halite\KeyFactory;
use Psr\Container\ContainerInterface;
use function DI\autowire;
use function DI\get;

return [
    'circli.auth.remember-me.cookie-factory' => static function(ContainerInterface $container) {
        $config = $container->get(Config::class);
        $keyFile = $config->get('auth.remember-me.encryption-key');
        if (file_exists($keyFile)) {
            $encryptionKey = KeyFactory::loadEncryptionKey($keyFile);
        }
        else {
            $encryptionKey = KeyFactory::generateEncryptionKey();
            KeyFactory::save($encryptionKey, $keyFile);
        }
        return new EncryptedCookieFactory(new Cookie($encryptionKey));
    },
    RememberMeMiddleware::class => autowire(RememberMeMiddleware::class)
        ->constructorParameter('cookieFactory', get('circli.auth.remember-me.cookie-factory')),
];
