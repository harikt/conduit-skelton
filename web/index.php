<?php
use Aura\Dispatcher\Dispatcher;
use Phly\Conduit\Middleware;
use Phly\Http\Server;
use Conduit\Middleware\RouterMiddleware;
use Conduit\Middleware\AuthenticationMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv::load(dirname(__DIR__));

// Create the object and load the $di
require dirname(__DIR__) . '/config/di.php';

$dispatcher = new Dispatcher;
$dispatcher->setObjectParam('controller');
$dispatcher->setMethodParam('action');

require dirname(__DIR__) . '/config/routes.php';
require dirname(__DIR__) . '/config/controllers.php';

$app = new Middleware();
$app->pipe('/admin', function ($req, $res, $next) use ($di) {
    $middleware = $di->newInstance('Conduit\Middleware\AuthenticationMiddleware');
    $middleware->handle($req, $res, $next);
});
$routerMiddleware = new RouterMiddleware($router, $dispatcher);
$app->pipe($routerMiddleware);
$server = Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$server->listen();
