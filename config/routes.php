<?php
$router = $di->get('router');
$router->add('home', '/')
    ->addValues(array('controller' => function () use ($di) {
        $twig = $di->get('twig');
        return $twig->render('home.html');
    }))
;

$router->add('blog.browse', '/blog')
    ->addValues(array(
        'controller' => 'Controller\Blog',
        'action' => 'browse'
    ))
;

$router->add('blog.view', '/blog/view/{id}')
    ->addValues(array(
        'controller' => 'Controller\Blog',
        'action' => 'view'
    ))
;

$router->addGet('login', '/login')
    ->addValues(array(
        'controller' => function () use ($di) {
            $twig = $di->get('twig');
            return $twig->render('login.html');
        }
    ))
;

$router->addPost('login.post', '/login')
    ->addValues(array(
        'controller' => 'Controller\Login',
        'action' => 'post'
    ))
;

$router->add('logout', '/logout')
    ->addValues(array(
        'controller' => 'Controller\Logout',
    ))
;

$router->add('admin', '/admin')
    ->addValues(array(
        'controller' => function () use ($di) {
            $twig = $di->get('twig');
            return $twig->render('admin.html');
        },
    ))
;

$router->addGet('contact', '/contact')
    ->addValues(array(
        'controller' => function ($response) use ($di) {
            $twig = $di->get('twig');
            return $twig->render('contact.html');
        },
    ))
;

$router->addPost('contact.post', '/contact')
    ->addValues(array(
        'controller' => function ($request, $response) use ($di) {
            $post = $request->getParsedBody();
            $subject = $post['contact'];
            $contact_form = new \Form\ContactForm();
            $twig = $di->get('twig');
            if ($contact_form->isValid($subject)) {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', '/thankyou');
            }

            return $twig->render('contact.html', array('filter' => $contact_form->getInputFilter(), 'contact' => $subject));
        },
    ))
;

$router->addGet('thankyou', '/thankyou')
    ->addValues(array(
        'controller' => function () use ($di) {
            $twig = $di->get('twig');
            return $twig->render('thankyou.html');
        },
    ))
;
