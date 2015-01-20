<?php
$router = $di->get('router');
$router->add('home', '/')
    ->addValues(array('controller' => 'homepage'))
;

$router->add('blog.browse', '/blog')
    ->addValues(array(
        'controller' => 'blog',
        'action' => 'browse'
    ))
;

$router->add('blog.view', '/blog/view/{id}')
    ->addValues(array(
        'controller' => 'blog',
        'action' => 'view'
    ))
;

$router->addGet('login', '/login/')
    ->addValues(array(
        'controller' => 'login',
        'action' => 'get'
    ))
;

$router->addPost('login.post', '/login/')
    ->addValues(array(
        'controller' => 'login',
        'action' => 'post'
    ))
;

$router->add('logout', '/logout/')
    ->addValues(array(
        'controller' => 'logout',
    ))
;

$router->add('admin', '/admin/')
    ->addValues(array(
        'controller' => 'admin',
    ))
;

$router->addGet('contact', '/contact')
    ->addValues(array(
        'controller' => 'contact',
    ))
;

$router->addPost('contact.post', '/contact')
    ->addValues(array(
        'controller' => 'contact.post',
    ))
;

$router->addGet('thankyou', '/thankyou')
    ->addValues(array(
        'controller' => 'thankyou',
    ))
;
