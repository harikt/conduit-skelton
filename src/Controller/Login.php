<?php
namespace Controller;

use Auth\PdoAdapter;
use PDO;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig_Environment;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;

class Login
{
    private $auth;

    private $pdo;

    private $twig;

    public function __construct(Twig_Environment $twig, AuthenticationService $auth, PDO $pdo)
    {
        $this->twig = $twig;
        $this->auth = $auth;
        $this->pdo  = $pdo;
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getBodyParams();
        $adapter = new PdoAdapter($this->pdo, 'users', 'username', 'password');
        $adapter->setCredential($data['password'])
            ->setIdentity($data['username']);
        $result = $this->auth->authenticate($adapter);
        if ($result->getCode() === Result::SUCCESS) {
            return $response
                ->withStatus(302)
                ->withHeader('Location', '/admin');
        }
        return $this->twig->render('login.html');
    }
}
