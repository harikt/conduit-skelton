# Conduit Experiments

Check [conduit](https://github.com/phly/conduit) the middleware for PHP.

This is pretty simple configuring some of the components of aura with conduit. Yes a [router middleware is here](src/Conduit/Middleware/RouterMiddleware.php).

## Installation

```sh
git clone https://github.com/harikt/ConduitExperiments
cd ConduitExperiments
composer install
php -S locahost:8000 web/index.php
```

## Usage

Configure your routes.

```php
<?php
// config/routes.php
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
```

Time to configure your controller according to the route.

```php
<?php
// config/controllers.php
$dispatcher->setObject('homepage', function ($response) {
    $response->setHeader('Content-Type', 'text/html');
    $response->getBody()->write("<p>Home page in html. Please <a href=\"blog\">Browse</a> and <a href=\"blog/12\">view post</a></p>");
});

$dispatcher->setObject('blog', $di->lazyNew('Controller\Blog'));
/*
// or without a DI container as
$dispatcher->setObject('blog', function () {
    return new Controller\Blog();
});
*/
```

You can make use of closure or [dependency injection container](https://github.com/auraphp/Aura.Di).

Play and enjoy!
