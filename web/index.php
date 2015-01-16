<?php
use Aura\Di\Container;
use Aura\Di\Factory;
use Aura\Router\Router;
use Aura\Router\RouteCollection;
use Aura\Router\RouteFactory;
use Aura\Router\Generator;
use Aura\Dispatcher\Dispatcher;
use Phly\Conduit\Middleware;
use Phly\Http\Server;
use Conduit\Middleware\RouterMiddleware;
use Conduit\Middleware\AuthenticationMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv::load(dirname(__DIR__));

$dispatcher = new Dispatcher;
$dispatcher->setObjectParam('controller');
$dispatcher->setMethodParam('action');

$router = new Router(
    new RouteCollection(new RouteFactory),
    new Generator
);

$di = new Container(new Factory);

require dirname(__DIR__) . '/config/di.php';
require dirname(__DIR__) . '/config/routes.php';
require dirname(__DIR__) . '/config/controllers.php';

$routerMiddleware = new RouterMiddleware($router, $dispatcher);

$auth = $di->get('aura/auth:auth');
$resume_service = $di->get('aura/auth:resume_service');
$authMiddleware = new AuthenticationMiddleware($auth, $resume_service);

$app = new Middleware();
$app->pipe('/admin', $authMiddleware);
$app->pipe($routerMiddleware);
$server = Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$server->listen();
