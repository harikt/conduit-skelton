<?php
namespace Conduit\Middleware;

use Phly\Conduit\Middleware;
use Psr\Http\Message\IncomingRequestInterface as Request;
use Psr\Http\Message\OutgoingResponseInterface as Response;
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

    public function handle(Request $request, Response $response, callable $next = null)
    {
        $path = $request->getUrl()->path;
        $route = $this->router->match($path, $request->getServerParams());
        if (! $route) {
            return $next();
        }
        $params = $route->params;
        $params['request'] = $request;
        $params['response'] = $response;
        $this->dispatcher->__invoke($params);
    }
}
