# Conduit Experiments

Check [conduit](https://github.com/phly/conduit) the middleware for PHP.

This is pretty simple configuring some of the components of aura with conduit. Currently have [application middleware](src/Conduit/Middleware/ApplicationMiddleware.php) and  [authentication middleware](src/Conduit/Middleware/AuthenticationMiddleware.php).

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
    ->addValues(array('controller' => function ($response) {
            return $response->getBody()->write("<p>Home page in html. Please <a href=\"blog\">Browse</a> and <a href=\"blog/12\">view post</a></p>")->withHeader('Content-Type', 'text/html');
        }
    ));

// or

$router->add('greet', '/greet')
    ->addValues(array(
        'controller' => 'Controller\Greet',
        'action' => 'hello'
    ));
```

```php
namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Response\Payload;

class Blog
{
    public function hello()
    {
        $available = array(
            'text/html' => '.html',
            'application/json' => '.json',
        );
        // $data, $view, $layout, $available
        return new Payload(array('name' => 'Hari KT'), 'greet', null, $available);
    }

    public function returnString()
    {
        return "Hello World";
    }

    public function returnResponse(ResponseInterface $response)
    {
        return $response->withStatus(200)
                    ->withHeader('Content-Type', 'text/html')
                    ->write("returns response");
    }
}
```

## Some conventions

Your view name expected is `greet.html.php`, `greet.json.php`, `greet.html.twig`, `greet.json.twig` formats.

Same for the layouts.

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

