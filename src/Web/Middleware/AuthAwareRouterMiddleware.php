<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Web\Middleware;

use Circli\Modules\Auth\Auth;
use Circli\Modules\Auth\Events\RouteAccessRequest;
use Circli\Modules\Auth\Web\Actions\AccessDeniedAction;
use Circli\Modules\Auth\Web\RequestAttributeKeys;
use Polus\Router\RouteInterface;
use Polus\Router\RouterDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthAwareRouterMiddleware implements MiddlewareInterface
{
    /** @var RouterDispatcherInterface */
    protected $routerDispatcher;

    public function __construct(RouterDispatcherInterface $routerDispatcher)
    {
        $this->routerDispatcher = $routerDispatcher;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->routerDispatcher->dispatch($request);

        /** @var Auth $auth */
        $auth = $request->getAttribute(RequestAttributeKeys::AUTH);
        if (!$auth->haveAccess(new RouteAccessRequest($route, $auth))) {
            $request = $request->withAttribute('denied_route', $route);
            $route = new class implements RouteInterface {
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
                    return new AccessDeniedAction();
                }

                public function getMethod()
                {
                    return 'GET';
                }

                public function getAttributes(): array
                {
                    return [];
                }
            };
        }

        if (count($route->getAttributes())) {
            foreach ($route->getAttributes() as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }
        }
        $request = $request->withAttribute('route', $route);

        return $handler->handle($request);
    }
}