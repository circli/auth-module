<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Middleware;

use Circli\Modules\Auth\Auth;
use Circli\Modules\Auth\Events\RouteAccessRequest;
use Circli\Modules\Auth\Web\Actions\AccessDeniedActionInterface;
use Circli\Modules\Auth\Web\RequestAttributeKeys;
use Polus\Adr\Interfaces\ActionInterface;
use Polus\Router\RouteInterface;
use Polus\Router\RouterDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthAwareRouterMiddleware implements MiddlewareInterface
{
    /** @var AccessDeniedActionInterface */
    private $accessDeniedAction;

    public function __construct(AccessDeniedActionInterface $accessDeniedAction)
    {
        $this->accessDeniedAction = $accessDeniedAction;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('route');
        if ($route && $route->getStatus() === RouterDispatcherInterface::FOUND) {
            /** @var Auth $auth */
            $auth = $request->getAttribute(RequestAttributeKeys::AUTH);
            if (!$auth->haveAccess(new RouteAccessRequest($route, $auth))) {
                $request = $request->withAttribute('denied_route', $route);
                $newRoute = new class($this->accessDeniedAction, $route) implements RouteInterface {
                    /** @var ActionInterface */
                    private $action;
                    /** @var RouteInterface */
                    private $route;

                    public function __construct(ActionInterface $action, RouteInterface $route)
                    {
                        $this->action = $action;
                        $this->route = $route;
                    }

                    public function getStatus(): int
                    {
                        return RouterDispatcherInterface::FOUND;
                    }

                    public function getAllows(): array
                    {
                        return ['GET', 'POST'];
                    }

                    public function getHandler()
                    {
                        return $this->action;
                    }

                    public function getMethod()
                    {
                        return $this->route->getMethod();
                    }

                    public function getAttributes(): array
                    {
                        return $this->route->getAttributes();
                    }
                };
                $request = $request->withAttribute('route', $newRoute);
            }
        }

        return $handler->handle($request);
    }
}