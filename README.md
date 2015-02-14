# Conduit Experiments

Check [conduit](https://github.com/phly/conduit) the middleware for PHP.

This is pretty simple configuring some of the components of aura with conduit. Currently have [negotiation middleware](src/Conduit/Middleware/NegotiationMiddleware.php), [router middleware](src/Conduit/Middleware/RouterMiddleware.php) and  [authentication middleware](src/Conduit/Middleware/AuthenticationMiddleware.php).

## Installation

```sh
git clone https://github.com/harikt/conduit-skelton
cd conduit-skelton
composer install
php -S locahost:8000 web/index.php
```

## Usage

Configure your routes.

```php
<?php
// config/routes.php
$router = $di->get('router');
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
$dispatcher = $di->get('dispatcher');
$dispatcher->setObject('homepage', function ($response) {
    return $response->getBody()->write("<p>Home page in html. Please <a href=\"blog\">Browse</a> and <a href=\"blog/12\">view post</a></p>")->withHeader('Content-Type', 'text/html');
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

## Configuring authentication middleware

Try `http://localhost:8000/admin` . If you are not logged in it will redirect you to `http://localhost:8000/login` page.

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT 'Username',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `password` varchar(255) NOT NULL COMMENT 'Password',
  `fullname` varchar(255) NOT NULL COMMENT 'Full name',
  `website` varchar(255) DEFAULT NULL COMMENT 'Website',
  `active` int(11) NOT NULL COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Environmental variables are used with the help of [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv). Rename `env_dist` to `.env` and change database name, username and password according to yours.

Play and enjoy!

## Thanks

* [Aura](https://github.com/auraphp)
* [Jeremy Kendall](http://github.com/jeremykendall/slim-auth)
* [Matthew Weier O'Phinney](https://github.com/weierophinney)
* [Paul M Jones](https://github.com/pmjones)
* [Zend framework](https://github.com/zendframework/zf2/)

