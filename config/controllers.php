<?php
$dispatcher->setObject('homepage', function ($response) {
    $response->setHeader('Content-Type', 'text/html');
    $response->getBody()->write("<p>Home page in html. Please <a href=\"/blog\">Browse</a> and <a href=\"/blog/12\">view post</a></p>");
});

$dispatcher->setObject('login', $di->lazyNew('Controller\Login'));

$dispatcher->setObject('logout', $di->lazyNew('Controller\Logout'));

$dispatcher->setObject('blog', $di->lazyNew('Controller\Blog'));

$dispatcher->setObject('admin', function ($response) {
    $response->setHeader('Content-Type', 'text/html');
    $response->getBody()->write("<p>Admin <a href=\"/logout\">Logout</a></p>");
});
