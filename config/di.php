<?php
use Aura\Di\Container;
use Aura\Di\Factory;

$di = new Container(new Factory);

$di->params['Aura\Router\RouteCollection'] = array(
    'route_factory' => $di->lazyNew('Aura\Router\RouteFactory'),
);

$di->params['Aura\Router\Router'] = array(
    'routes' => $di->lazyNew('Aura\Router\RouteCollection'),
    'generator' => $di->lazyNew('Aura\Router\Generator'),
);

$di->set('router', $di->lazyNew('Aura\Router\Router'));

/**
 * Services
 */
$di->set('aura/auth:auth', $di->lazyNew('Aura\Auth\Auth'));
$di->set('aura/auth:login_service', $di->lazyNew('Aura\Auth\Service\LoginService'));
$di->set('aura/auth:logout_service', $di->lazyNew('Aura\Auth\Service\LogoutService'));
$di->set('aura/auth:resume_service', $di->lazyNew('Aura\Auth\Service\ResumeService'));
$di->set('aura/auth:session', $di->lazyNew('Aura\Auth\Session\Session'));
$di->set('aura/auth:adapter', $di->lazyNew('Aura\Auth\Adapter\NullAdapter'));

/**
 * Aura\Auth\Adapter\HtpasswdAdapter
 */
$di->params['Aura\Auth\Adapter\HtpasswdAdapter'] = array(
    'verifier' => $di->lazyNew('Aura\Auth\Verifier\HtpasswdVerifier'),
);

/**
 * Aura\Auth\Adapter\ImapAdapter
 */
$di->params['Aura\Auth\Adapter\ImapAdapter'] = array(
    'phpfunc' => $di->lazyNew('Aura\Auth\Phpfunc'),
);

/**
 * Aura\Auth\Adapter\LdapAdapter
 */
$di->params['Aura\Auth\Adapter\LdapAdapter'] = array(
    'phpfunc' => $di->lazyNew('Aura\Auth\Phpfunc'),
);

/**
 * Aura\Auth\Adapter\PdoAdapter
 */
$di->params['Aura\Auth\Adapter\PdoAdapter'] = array(
    'verifier' => $di->lazyNew('Aura\Auth\Verifier\PasswordVerifier'),
    'from' => 'users',
    'cols' => array('username', 'password'),
);

/**
 * Aura\Auth\Auth
 */
$di->params['Aura\Auth\Auth'] = array(
    'segment' => $di->lazyNew('Aura\Auth\Session\Segment')
);

/**
 * Aura\Auth\Service\LoginService
 */
$di->params['Aura\Auth\Service\LoginService'] = array(
    'adapter' => $di->lazyGet('aura/auth:adapter'),
    'session' => $di->lazyGet('aura/auth:session')
);

/**
 * Aura\Auth\Service\LogoutService
 */
$di->params['Aura\Auth\Service\LogoutService'] = array(
    'adapter' => $di->lazyGet('aura/auth:adapter'),
    'session' => $di->lazyGet('aura/auth:session')
);

/**
 * Aura\Auth\Service\ResumeService
 */
$di->params['Aura\Auth\Service\ResumeService'] = array(
    'adapter' => $di->lazyGet('aura/auth:adapter'),
    'session' => $di->lazyGet('aura/auth:session'),
    'timer' => $di->lazyNew('Aura\Auth\Session\Timer'),
    'logout_service' => $di->lazyGet('aura/auth:logout_service'),
);

/**
 * Aura\Auth\Session\Timer
 */
$di->params['Aura\Auth\Session\Timer'] = array(
    'ini_gc_maxliftime' => ini_get('session.gc_maxlifetime'),
    'ini_cookie_liftime' => ini_get('session.cookie_lifetime'),
    'idle_ttl' => 1440,
    'expire_ttl' => 14400,
);

/**
 * Aura\Auth\Session\Session
 */
$di->params['Aura\Auth\Session\Session'] = array(
    'cookie' => $_COOKIE,
);

/**
 * Aura\Auth\Verifier\PasswordVerifier
 */
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
    )
);
$di->set('twig', $di->lazyNew('Twig_Environment'));
$di->params['Conduit\Middleware\AuthenticationMiddleware'] = array(
    'auth' => $di->lazyGet('aura/auth:auth'),
    'resume_service' => $di->lazyGet('aura/auth:resume_service'),
);
