<?php
namespace Conduit\Middleware;

use Phly\Conduit\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Router\Router;
use Aura\Dispatcher\Dispatcher;

class RouterMiddleware
{
    private $router;

    private $dispatcher;

    public function __construct(Router $router, Dispatcher $dispatcher)
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // https://github.com/phly/http/issues/19
        $path = $request->getUri()->getPath();
        $route = $this->router->match($path, $request->getServerParams());
        if (! $route) {
            return $next();
        }
        $params = $route->params;
        $params['request'] = $request;
        $params['response'] = $response;
        $data = $this->dispatcher->__invoke($params);
    }
}
