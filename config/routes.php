<?php
$router->add('home', '/')
    ->addValues(array('controller' => 'homepage'));

$router->add('blog.browse', '/blog')
    ->addValues(array(
        'controller' => 'blog',
        'action' => 'browse'
    ));

$router->add('blog.view', '/blog/{id}')
    ->addValues(array(
        'controller' => 'blog',
        'action' => 'view'
    ));
