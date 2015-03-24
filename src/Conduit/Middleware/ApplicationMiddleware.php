<?php
namespace Conduit\Middleware;

use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Router\Router;
use Aura\Dispatcher\Dispatcher;
use Aura\Di\Container;

class ApplicationMiddleware implements MiddlewareInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $router = $this->container->get('router');
        $dispatcher = $this->container->get('dispatcher');
        $server = $request->getServerParams();
        $path = parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        $route = $router->match($path, $server);
        if (! $route) {
            return $next($request, $response);
        }
        $params = $route->params;
        if (is_string($params['controller']) && 
            ! $this->dispatcher->hasObject($params['controller'])
        ) {
            // create the controller object
            $params['controller'] = $this->container->newInstance($params['controller']);
        }
        $params['request'] = $request;
        $params['response'] = $response;
        $result = $dispatcher->__invoke($params);
        if ($result instanceof ResponseInterface) {
            return $result;
        }
        if (is_string($result)) {
            return $response->write($result);
        }
        return $response;
    }
}
