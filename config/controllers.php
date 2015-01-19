<?php
$dispatcher = $di->get('dispatcher');
$dispatcher->setObject('homepage', function ($response) use ($di) {
    $twig = $di->get('twig');
    $response->withHeader('Content-Type', 'text/html')
        ->write($twig->render('home.html'));
});

$dispatcher->setObject('login', $di->lazyNew('Controller\Login'));

$dispatcher->setObject('logout', $di->lazyNew('Controller\Logout'));

$dispatcher->setObject('blog', $di->lazyNew('Controller\Blog'));

$dispatcher->setObject('admin', function ($response) use ($di) {
    $twig = $di->get('twig');
    $response->withHeader('Content-Type', 'text/html')
        ->write($twig->render('admin.html'));
});

$dispatcher->setObject('contact', function ($response) use ($di) {
    $twig = $di->get('twig');
    $response->withHeader('Content-Type', 'text/html')
        ->write($twig->render('contact.html'));
});

$dispatcher->setObject('contact.post', function ($request, $response) use ($di) {
    $post = $request->getBodyParams();
    $subject = $post['contact'];
    $contact_form = new \Form\ContactForm();
    $twig = $di->get('twig');
    if ($contact_form->isValid($subject)) {
        $response->withHeader('Location', '/thankyou')
            ->end();
    } else {
        $response->withHeader('Content-Type', 'text/html')
            ->write($twig->render('contact.html', array('filter' => $contact_form->getFilter(), 'contact' => $subject)));
    }
});

$dispatcher->setObject('thankyou', function ($response) use ($di) {
    $twig = $di->get('twig');
    $response->withHeader('Content-Type', 'text/html')
        ->write($twig->render('thankyou.html'));
});
