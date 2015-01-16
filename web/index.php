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

require dirname(__DIR__) . '/vendor/autoload.php';

$dispatcher = new Dispatcher;
$dispatcher->setObjectParam('controller');
$dispatcher->setMethodParam('action');

$router = new Router(
    new RouteCollection(new RouteFactory),
    new Generator
);

$di = new Container(new Factory);

require dirname(__DIR__) . '/config/routes.php';
require dirname(__DIR__) . '/config/controllers.php';
require dirname(__DIR__) . '/config/di.php';

$routermiddleware = new RouterMiddleware($router, $dispatcher);

$app = new Middleware();
$app->pipe($routermiddleware);
$server = Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$server->listen();
