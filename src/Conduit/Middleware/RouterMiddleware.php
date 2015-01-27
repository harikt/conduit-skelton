<?php
namespace Conduit\Middleware;

use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Router\Router;
use Aura\Dispatcher\Dispatcher;

class RouterMiddleware implements MiddlewareInterface
{
    private $router;

    private $dispatcher;

    public function __construct(Router $router, Dispatcher $dispatcher)
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $path = $request->getUri()->getPath();
        $route = $this->router->match($path, $request->getServerParams());
        if (! $route) {
            return $next($request, $response);
        }
        $params = $route->params;
        $params['request'] = $request;
        $params['response'] = $response;
        $result = $this->dispatcher->__invoke($params);
        if ($result instanceof ResponseInterface) {
            return $result;
        }
        if (is_string($result)) {
            return $response->write($result);
        }
        return $response;
    }
}
