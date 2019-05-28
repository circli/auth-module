<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events\Providers;

use Circli\Modules\Auth\Authentication\Data\PasswordLoginData;
use Circli\Modules\Auth\Authentication\TokenProviders;
use Circli\Modules\Auth\Events\LoginSuccess;
use Circli\Modules\Auth\Events\Logout;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\WebCore\Contracts\CookieInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class RememberMeProvider implements ListenerProviderInterface
{
    /** @var CookieInterface|null */
    private $cookie;
    /** @var AccountTokenRepositoryInterface */
    private $tokenRepository;

    public function __construct(AccountTokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function setCookie(CookieInterface $cookie): void
    {
        $this->cookie = $cookie;
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        if ($this->cookie) {
            //handle remember_me token persisting
            if ($event instanceof LoginSuccess) {
                yield from [function (LoginSuccess $event) {
                    $loginData = $event->getLoginData();
                    if ($loginData instanceof PasswordLoginData && $loginData->rememberMe()) {
                        $expire = new \DateTimeImmutable('+2 weeks');
                        $rememberMeToken = $this->tokenRepository->create(
                            $event->getAccount(),
                            TokenProviders::REMEMBER_ME,
                            $expire
                        );

                        $this->cookie->setExpire($expire);
                        $this->cookie->setValue((string)$rememberMeToken);
                        $this->cookie->save();
                    }
                }];
            }
            if ($event instanceof Logout && $this->cookie->exits()) {
                yield from [function() {
                    $this->cookie->delete();
                }];
            }
        }
    }
}