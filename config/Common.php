<?php
namespace Skelton\_Config;
use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('dispatcher', $di->lazyNew('Aura\Dispatcher\Dispatcher'));
        $di->set('router', $di->lazyNew('Aura\Router\Router'));
        $di->set('accept', $di->lazyNew('Aura\Accept\Accept'));
        $di->params['Aura\Dispatcher\Dispatcher'] = array(
            'objects' => array(),
            'object_param' => 'controller',
            'method_param' => 'action',
        );

        $di->params['Aura\Auth\Adapter\PdoAdapter']['pdo'] = $di->lazyGet('db');
        $di->set('aura/auth:adapter', $di->lazyNew('Aura\Auth\Adapter\PdoAdapter'));

        $di->set('db', $di->lazyNew('Pdo'));
        $di->params['Pdo'] = array(
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'passwd' => getenv('DB_PASSWORD'),
        );
        // Login controller
        $di->params['Controller\Login']['twig'] = $di->lazyGet('twig');
        $di->params['Controller\Login']['pdo'] = $di->lazyGet('db');
        $di->params['Controller\Login']['auth'] = $di->lazyGet('authentication_service');

        $di->params['Controller\Logout']['auth'] = $di->lazyGet('authentication_service');

        $di->set('authentication_service', $di->lazyNew('Zend\Authentication\AuthenticationService'));

        // Blog controller
        $di->params['Controller\Blog']['twig'] = $di->lazyGet('twig');

        // Twig
        $di->params['Twig_Loader_Filesystem']['paths'] = array(dirname(__DIR__) . '/templates');
        $di->params['Twig_Environment'] = array (
            'loader' => $di->lazyNew('Twig_Loader_Filesystem'),
            'options' => array(
                'cache' => dirname(__DIR__) . '/cache',
                'debug' => true,
            )
        );
        $di->set('twig', $di->lazyNew('Twig_Environment'));

        // Authentication middleware
        $di->params['Conduit\Middleware\AuthenticationMiddleware'] = array(
            'auth' => $di->lazyGet('authentication_service'),
        );

        // Router middleware
        $di->params['Conduit\Middleware\RouterMiddleware'] = array(
            'router' => $di->lazyGet('router'),
            'dispatcher' => $di->lazyGet('dispatcher'),
        );

        // Negotiation middleware
        $di->params['Conduit\Middleware\NegotiationMiddleware'] = array(
            'accept' => $di->lazyGet('accept'),
        );

        $di->set('auth_middleware', $di->lazyNew('Conduit\Middleware\AuthenticationMiddleware'));
        $di->set('router_middleware', $di->lazyNew('Conduit\Middleware\RouterMiddleware'));
        $di->set('negotiation_middleware', $di->lazyNew('Conduit\Middleware\NegotiationMiddleware'));

    }

    public function modify(Container $di)
    {
        // You can also add routes and controller here
    }
}
