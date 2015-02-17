<?php
$dispatcher = $di->get('dispatcher');
$dispatcher->setObject('homepage', function () use ($di) {
    $twig = $di->get('twig');
    return $twig->render('home.html');
});

$dispatcher->setObject('login', $di->lazyNew('Controller\Login'));

$dispatcher->setObject('logout', $di->lazyNew('Controller\Logout'));

$dispatcher->setObject('blog', $di->lazyNew('Controller\Blog'));

$dispatcher->setObject('admin', function () use ($di) {
    $twig = $di->get('twig');
    return $twig->render('admin.html');
});

$dispatcher->setObject('contact', function ($response) use ($di) {
    $twig = $di->get('twig');
    return $twig->render('contact.html');
});

$dispatcher->setObject('contact.post', function ($request, $response) use ($di) {
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
});

$dispatcher->setObject('thankyou', function () use ($di) {
    $twig = $di->get('twig');
    return $twig->render('thankyou.html');
});
