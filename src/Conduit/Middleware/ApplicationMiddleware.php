<?php
namespace Conduit\Middleware;

use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Accept\Accept;
use Aura\Di\Container;
use Aura\Dispatcher\Dispatcher;
use Aura\Router\Router;
use FOA\Responder_Bundle\Renderer\RendererInterface;
use Response\Payload;

class ApplicationMiddleware implements MiddlewareInterface
{
    protected $accept;

    protected $di;

    protected $dispatcher;

    protected $router;

    protected $renderer;

    public function __construct(
        Accept $accept,
        Container $di,
        Dispatcher $dispatcher,
        Router $router,
        RendererInterface $renderer
    ) {
        $this->accept = $accept;
        $this->di = $di;
        $this->dispatcher = $dispatcher;
        $this->router = $router;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $server = $request->getServerParams();
        $path = parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        $route = $this->router->match($path, $server);
        if (! $route) {
            return $next($request, $response);
        }
        $params = $route->params;
        if (is_string($params['controller']) &&
            ! $this->dispatcher->hasObject($params['controller'])
        ) {
            // create the controller object
            $params['controller'] = $this->di->newInstance($params['controller']);
        }
        $params['request'] = $request;
        $params['response'] = $response;
        $payload = $this->dispatcher->__invoke($params);

        if ($payload instanceof Payload) {
            $available = $payload->getAvailable();
            $available_types = array_keys($available);
            $media = $this->accept->negotiateMedia($available_types);
            if (! $media) {
                $response = $response->withStatus(406)
                    ->withHeader('Content-Type', 'text/plain')
                    ->withBody(implode(',', $available_types));
            } else {
                // expects .json, .html etc
                $extension = $available[$media->getValue()];
                $view = (null !== $payload->getView()) ? $payload->getView() . $extension : null;
                $layout = (null !== $payload->getLayout()) ? $payload->getLayout() . $extension : null;
                $content = $this->renderer->render($payload->getData(), $view, $layout);
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', $media->getValue())
                    ->write($content);
            }
        }

        if ($payload instanceof ResponseInterface) {
            $response = $payload;
        }

        if (is_string($payload)) {
            $response = $response->write($payload);
        }

        return $next($request, $response);
    }
}
