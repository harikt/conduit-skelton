<?php
$dispatcher = $di->get('dispatcher');
$dispatcher->setObject('homepage', function ($response) use ($di) {
    $twig = $di->get('twig');
    $response->setHeader('Content-Type', 'text/html');
    $response->getBody()->write($twig->render('home.html'));
});

$dispatcher->setObject('login', $di->lazyNew('Controller\Login'));

$dispatcher->setObject('logout', $di->lazyNew('Controller\Logout'));

$dispatcher->setObject('blog', $di->lazyNew('Controller\Blog'));

$dispatcher->setObject('admin', function ($response) use ($di) {
    $twig = $di->get('twig');
    $response->setHeader('Content-Type', 'text/html');
    $response->getBody()->write($twig->render('admin.html'));
});
