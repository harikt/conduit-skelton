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
        $di->params['Aura\Dispatcher\Dispatcher'] = array(
            'objects' => array(),
            'object_param' => 'controller',
            'method_param' => 'action',
        );
        $di->params['Aura\Auth\Verifier\PasswordVerifier'] = array(
            'algo' => PASSWORD_DEFAULT,
        );

        // Logout controller
        $di->params['Controller\Logout']['logout_service'] = $di->lazyGet('aura/auth:logout_service');
        $di->params['Controller\Logout']['resume_service'] = $di->lazyGet('aura/auth:resume_service');
        $di->params['Controller\Logout']['auth'] = $di->lazyGet('aura/auth:auth');

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
            'auth' => $di->lazyGet('aura/auth:auth'),
            'resume_service' => $di->lazyGet('aura/auth:resume_service'),
        );

        // Router middleware
        $di->params['Conduit\Middleware\RouterMiddleware'] = array(
            'router' => $di->lazyGet('router'),
            'dispatcher' => $di->lazyGet('dispatcher'),
        );
    }

    public function modify(Container $di)
    {
        // You can also add routes and controller here
    }
}
