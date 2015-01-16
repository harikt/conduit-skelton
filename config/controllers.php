<?php
$dispatcher->setObject('homepage', function ($response) {
    $response->setHeader('Content-Type', 'text/html');
    $response->getBody()->write("<p>Home page in html. Please <a href=\"blog\">Browse</a> and <a href=\"blog/12\">view post</a></p>");
});

$dispatcher->setObject('blog', $di->lazyNew('Controller\Blog'));
