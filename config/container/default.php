<?php

use Atlas\Orm\Atlas;
use Circli\Modules\Auth\Authentication\Handler;
use Circli\Modules\Auth\Authentication\PasswordHasher;
use Circli\Modules\Auth\Authentication\Provider\ProviderFactory;
use Circli\Modules\Auth\Authentication\Provider\ProviderFactoryInterface;
use Circli\Modules\Auth\DataSource\Account\Account;
use Circli\Modules\Auth\DataSource\Account\AccountMapperInterface;
use Circli\Modules\Auth\DataSource\AccountAccess\AccountAccess;
use Circli\Modules\Auth\DataSource\AccountAccess\AccountAccessMapperInterface;
use Circli\Modules\Auth\DataSource\AccountLoginLog\AccountLoginLog;
use Circli\Modules\Auth\DataSource\AccountLoginLog\LoginLogMapperInterface;
use Circli\Modules\Auth\DataSource\AccountRole\AccountRole;
use Circli\Modules\Auth\DataSource\AccountRole\AccountRoleMapperInterface;
use Circli\Modules\Auth\DataSource\AccountToken\AccountToken;
use Circli\Modules\Auth\DataSource\AccountToken\AccountTokenMapperInterface;
use Circli\Modules\Auth\DataSource\AccountValues\AccountValues;
use Circli\Modules\Auth\DataSource\AccountValues\AccountValuesMapperInterface;
use Circli\Modules\Auth\DataSource\AclRole\AclRole;
use Circli\Modules\Auth\DataSource\AclRoleAcces\AclRoleAcces;
use Circli\Modules\Auth\Repositories\AccessRepository;
use Circli\Modules\Auth\Repositories\AccessRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountRepository;
use Circli\Modules\Auth\Repositories\AccountRepositoryInterface;
use Circli\Modules\Auth\Repositories\AccountTokenRepository;
use Circli\Modules\Auth\Repositories\AccountTokenRepositoryInterface;
use Circli\Modules\Auth\Repositories\LoginLogRepository;
use Circli\Modules\Auth\Repositories\LoginLogRepositoryInterface;
use Circli\Modules\Auth\Session\DefaultFactory;
use Circli\Modules\Auth\Session\Factory as AuthSessionFactory;
use Circli\Modules\Auth\Voter\AccessCheckers;
use Circli\Modules\Auth\Voter\AuthRequiredActionVoter;
use Circli\Modules\Auth\Voter\DefaultAllowRouteVoter;
use Circli\Modules\Auth\Voter\RequireRoleActionVoter;
use Circli\Modules\Auth\Voter\GuestRouteVoter;
use Circli\Modules\Auth\Web\Actions\AccessDeniedAction;
use Circli\Modules\Auth\Web\Actions\AccessDeniedActionInterface;
use Circli\Modules\Auth\Web\Actions\RequireAuthInterface;
use Circli\Modules\Auth\Web\Actions\ViewLoginAction;
use Circli\Modules\Auth\Web\Actions\ViewLoginInterface;
use Circli\Modules\Auth\Web\Actions\ViewRegisterAction;
use Circli\Modules\Auth\Web\Actions\ViewRegisterInterface;
use Circli\Modules\Auth\Web\Input\BasicCreateAccountInput;
use Circli\Modules\Auth\Web\Input\CreateAccountInput;
use function DI\autowire;
use function DI\decorate;
use function DI\get;
use Psr\Container\ContainerInterface;

$defs = [
    PasswordHasher::class => static function() {
        return new PasswordHasher([]);
    },
    CreateAccountInput::class => autowire(BasicCreateAccountInput::class),
    AuthSessionFactory::class => autowire(DefaultFactory::class),
    AccountTokenRepositoryInterface::class => autowire(AccountTokenRepository::class),
    AccessRepositoryInterface::class => autowire(AccessRepository::class),
    AccountRepositoryInterface::class => autowire(AccountRepository::class),
    LoginLogRepositoryInterface::class => autowire(LoginLogRepository::class),
    ProviderFactoryInterface::class => static function(ContainerInterface $container) {
        return new ProviderFactory(static function($cls) use($container) {
            return $container->get($cls);
        });
    },
    Handler::class => autowire(),
    AccessCheckers::class =>  decorate(static function ($previous, ContainerInterface $container) {
        if (!$previous instanceof AccessCheckers) {
            $previous = new AccessCheckers();
        }
        $previous->addVoter($container->get(GuestRouteVoter::class));
        $previous->addVoter($container->get(RequireRoleActionVoter::class));
        $voter = $container->get(AuthRequiredActionVoter::class);
        $voter->addAction(RequireAuthInterface::class);
        $previous->addVoter($voter);
        $previous->addVoter($container->get(DefaultAllowRouteVoter::class));
        return $previous;
    }),
    AccessDeniedActionInterface::class => autowire(AccessDeniedAction::class),
    ViewLoginInterface::class => autowire(ViewLoginAction::class),
    ViewRegisterInterface::class => autowire(ViewRegisterAction::class),
];

$mappers = [
    Account::class => [AccountMapperInterface::class],
    AccountAccess::class => [AccountAccessMapperInterface::class],
    AccountLoginLog::class => [LoginLogMapperInterface::class],
    AccountRole::class => [AccountRoleMapperInterface::class],
    AccountToken::class => [AccountTokenMapperInterface::class],
    AccountValues::class => [AccountValuesMapperInterface::class],
    AclRole::class => [],
    AclRoleAcces::class => [],
];

foreach ($mappers as $mapper => $aliases) {
    $defs[$mapper] = DI\factory(static function (ContainerInterface $container, string $mapper) {
        return $container->get(Atlas::class)->mapper($mapper);
    })->parameter('mapper', $mapper);

    if (count($aliases)) {
        foreach ($aliases as $alias) {
            $defs[$alias] = get($mapper);
        }
    }
}

return $defs;
