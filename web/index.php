<?php
use Aura\Di\ContainerBuilder;
use Phly\Conduit\MiddlewarePipe;
use Phly\Conduit\FinalHandler;
use Phly\Conduit\Http\Request as RequestDecorator;
use Phly\Conduit\Http\Response as ResponseDecorator;
use Phly\Http\Response;
use Phly\Http\Server;
use Phly\Http\ServerRequestFactory;
use Conduit\Middleware\ApplicationMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';

try {
    Dotenv::load(dirname(__DIR__));
} catch (Exception $e) {
}

// pre-existing service objects as ['service_name' => $object_instance]
$services = array();

// config classes to call define() and modify() on
$config_classes = array(
    'Aura\Router\_Config\Common',
    'Aura\Accept\_Config\Common',
    'Skelton\_Config\Common'
);

// use the builder to create a container
$container_builder = new ContainerBuilder;
$di = $container_builder->newInstance(
    $services,
    $config_classes,
    ContainerBuilder::ENABLE_AUTO_RESOLVE
);

require dirname(__DIR__) . '/config/routes.php';

$app = new MiddlewarePipe();
$app->pipe('/admin', $di->get('auth_middleware'));
$app->pipe('/blog/edit', $di->get('auth_middleware'));
$app->pipe('/blog/delete', $di->get('auth_middleware'));
$app->pipe($di->get('negotiation_middleware'));
$app->pipe(new ApplicationMiddleware($di));
$request  = new RequestDecorator(ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
));
$response = new ResponseDecorator(new Response());
$server   = new Server($app, $request, $response);
$final = new FinalHandler([ 'env' => 'dev' ]);
$server->listen($final);
