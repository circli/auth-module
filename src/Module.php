<?php declare(strict_types=1);

namespace Circli\Modules\Auth;

use Circli\Contracts\InitAdrApplication;
use Circli\Contracts\InitCliApplication;
use Circli\Contracts\ModuleInterface;
use Circli\Contracts\PathContainer;
use Circli\Core\Events\PostContainerBuild;
use Circli\Modules\Auth\Command\AddAccountValue;
use Circli\Modules\Auth\Command\CreateAccount;
use Circli\Modules\Auth\Command\ViewAccount;
use Circli\Modules\Auth\Events\Providers\RememberMeProvider;
use Circli\Modules\Auth\Events\Providers\TemplateAssignProvider;
use Circli\Modules\Auth\Voter\AccessCheckers;
use Circli\Modules\Auth\Web\Actions\CreateAccountAction;
use Circli\Modules\Auth\Web\Actions\HandleLoginAction;
use Circli\Modules\Auth\Web\Actions\HandleLogoutAction;
use Circli\Modules\Auth\Web\Actions\ViewLoginInterface;
use Circli\Modules\Auth\Web\Actions\ViewRegisterInterface;
use Polus\Adr\Adr;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Symfony\Component\Console\Application;

final class Module implements ModuleInterface, ListenerProviderInterface, InitAdrApplication, InitCliApplication
{
    public function __construct(PathContainer $paths)
    {
    }

    public function configure(): array
    {
        return include dirname(__DIR__) . '/config/container/default.php';
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
        if ($event instanceof PostContainerBuild) {
            yield from [function(PostContainerBuild $event) {
                $diContainer = $event->getContainer()->getContainer();
                $providerStore = $event->getContainer()->getEventListenerProvider();
                $providerStore->addProvider($diContainer->get(TemplateAssignProvider::class));
                $providerStore->addProvider($diContainer->get(RememberMeProvider::class));
                $providerStore->addProvider($diContainer->get(AccessCheckers::class));
            }];
        }
    }

    public function initAdr(Adr $adr, ContainerInterface $container = null)
    {
        $adr->post('/auth/register', new CreateAccountAction());
        $adr->post('/auth/login', new HandleLoginAction());
        $adr->get('/auth/logout', new HandleLogoutAction());
        if ($container) {
            $adr->get('/auth/register', $container->get(ViewRegisterInterface::class));
            $adr->get('/auth/login', $container->get(ViewLoginInterface::class));
        }
    }

    public function initCli(Application $cli, ContainerInterface $container)
    {
        $cli->add($container->get(ViewAccount::class));
        $cli->add($container->get(AddAccountValue::class));
        $cli->add($container->get(CreateAccount::class));
    }
}