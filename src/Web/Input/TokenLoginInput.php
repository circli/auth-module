<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Input;

use Circli\Middlewares\RequestAttributeKeys;
use Circli\Modules\Auth\Authentication\Data\LoginDataInterface;
use Circli\Modules\Auth\Authentication\Data\TokenLoginData;
use Circli\Modules\Auth\Authentication\TokenProviders;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

final class TokenLoginInput
{
    private const DISABLED_PROVIDERS = [
        TokenProviders::SESSION,
        TokenProviders::ACTIVATION,
        TokenProviders::PASSWORD,
        TokenProviders::REMEMBER_ME,
    ];

    /**
     * @param ServerRequestInterface|RequestInterface $request
     * @return LoginDataInterface
     */
    public function __invoke(ServerRequestInterface $request): LoginDataInterface
    {
        if (strtolower($request->getMethod()) === 'post') {
            $body = $request->getParsedBody();
            if (!isset($body['provider'], $body['token'], $body['uid'])) {
                throw new \DomainException('Missing data for token bases sign in');
            }
            $uid = $body['uid'];
            $token = $body['token'];
            $provider = $body['provider'];
        }
        else {
            $uid = $request->getAttribute('uid', false);
            $token = $request->getAttribute('uid', false);
            $provider = $request->getAttribute('provider', false);
        }
        if (!($token && $uid && \in_array($provider, TokenProviders::ALL, true))) {
            throw new \DomainException('Missing data for token bases sign in');
        }
        if (in_array($provider, self::DISABLED_PROVIDERS, true)) {
            throw new \DomainException('Invalid token provider');
        }

        $userAgent = $request->getAttribute(RequestAttributeKeys::USER_AGENT, '');
        $ip = $request->getAttribute(RequestAttributeKeys::IP);

        return new TokenLoginData($provider, $uid, $token, $ip, $userAgent);
    }
}
