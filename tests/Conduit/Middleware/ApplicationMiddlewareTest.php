<?php
namespace Conduit\Middleware;

use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phly\Conduit\Http\Request as RequestDecorator;
use Phly\Conduit\Http\Response as ResponseDecorator;
use Phly\Http\Response;
use Phly\Http\ServerRequestFactory;
use Aura\Di\ContainerBuilder;
use FOA\Responder_Bundle\Renderer\RendererInterface;
use Response\Payload;
use PHPUnit_Framework_TestCase;
use Conduit\Controller\TestController;

class ApplicationMiddlewareTest extends PHPUnit_Framework_TestCase
{
    protected $application;

    protected $router;

    protected $di;

    protected $next;

    public function setUp()
    {
        $this->next = function ($request, $response) {
            return $response;
        };
    }

    public function getApplication()
    {
        $config_classes = array(
            'Aura\Router\_Config\Common',
            'Aura\Accept\_Config\Common',
            'Skelton\_Config\Common'
        );

        // use the builder to create a container
        $container_builder = new ContainerBuilder;
        $this->di = $di = $container_builder->newInstance(
            array(),
            $config_classes,
            ContainerBuilder::DISABLE_AUTO_RESOLVE
        );
        // $di->set('dispatcher', $di->lazyNew('Aura\Dispatcher\Dispatcher'));
        // $di->set('router', $di->lazyNew('Aura\Router\Router'));
        // $di->set('accept', $di->lazyNew('Aura\Accept\Accept'));

        $accept = $di->get('accept');
        $dispatcher = $di->get('dispatcher');
        $this->router = $router = $di->get('router');
        $factory = new \Aura\Html\HelperLocatorFactory;
        $helpers = $factory->newInstance();

        $engine = new \Aura\View\View(
            new \Aura\View\TemplateRegistry,
            new \Aura\View\TemplateRegistry,
            $helpers
        );

        $view_registry = $engine->getViewRegistry();
        $view_registry->set('greet.html', function() {
            echo "Welcome {$this->name}";
        });

        $view_registry->set('greet.json', function() {
            $data = array('name' => $this->name);
            echo json_encode($data);
        });

        $renderer = new \FOA\Responder_Bundle\Renderer\AuraView($engine);

        return new ApplicationMiddleware(
            $accept,
            $di,
            $dispatcher,
            $router,
            $renderer
        );
    }

    public function testClosureRouter()
    {
        $application = $this->getApplication();
        $router = $this->router;
        $router->add('home', '/')
            ->addValues(array('controller' => function () {
                return "Hello World";
            }))
        ;

        $request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
            array('REQUEST_URI' => '/'),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response = new ResponseDecorator(new Response());

        $response = $application->__invoke($request, $response, $this->next);
        $this->assertSame("Hello World", (string) $response->getBody());
    }

    public function testControllerReturnsString()
    {
        $application = $this->getApplication();
        $router = $this->router;
        $router->add('home', '/')
            ->addValues(array(
                'controller' => 'Conduit\Controller\TestController',
                'action' => 'returnString'
            ))
        ;

        $request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
            array('REQUEST_URI' => '/'),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response = new ResponseDecorator(new Response());

        $response = $application->__invoke($request, $response, $this->next);
        $this->assertSame("Hello from return string method", (string) $response->getBody());
    }

    public function testControllerReturnsPayload()
    {
        $application = $this->getApplication();
        $router = $this->router;
        $router->add('home', '/')
            ->addValues(array(
                'controller' => 'Conduit\Controller\TestController',
                'action' => 'returnPayload'
            ))
        ;

        $request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
            array('REQUEST_URI' => '/'),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response = new ResponseDecorator(new Response());

        $response = $application->__invoke($request, $response, $this->next);
        $this->assertSame("Welcome Hari KT", (string) $response->getBody());
    }

    public function testControllerReturnResponse()
    {
        $application = $this->getApplication();
        $router = $this->router;
        $router->add('home', '/')
            ->addValues(array(
                'controller' => 'Conduit\Controller\TestController',
                'action' => 'returnResponse'
            ))
        ;

        $request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
            array('REQUEST_URI' => '/'),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response = new ResponseDecorator(new Response());

        $response = $application->__invoke($request, $response, $this->next);
        $this->assertSame("returns response", (string) $response->getBody());
    }

    public function testReturnPayloadWithAvailable()
    {
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $application = $this->getApplication();
        $router = $this->router;
        $router->add('home', '/')
            ->addValues(array(
                'controller' => 'Conduit\Controller\TestController',
                'action' => 'returnPayloadWithAvailable'
            ))
        ;

        $request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
            array('REQUEST_URI' => '/'),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response = new ResponseDecorator(new Response());

        $response = $application->__invoke($request, $response, $this->next);
        $expected = json_encode(array('name' => 'Hari KT'));
        $this->assertSame($expected, (string) $response->getBody());
    }

    public function testNoRouteMatched()
    {
        $application = $this->getApplication();

        $request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
            array('REQUEST_URI' => '/'),
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
        $response = new ResponseDecorator(new Response());

        $response = $application->__invoke($request, $response, function ($request, $response) {
            return $response->write("No route");
        });
        $this->assertSame("No route", (string) $response->getBody());
    }
}
