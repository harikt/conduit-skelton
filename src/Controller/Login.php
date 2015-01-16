<?php
namespace Controller;

use Twig_Environment;
use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Service\LoginService;
use Psr\Http\Message\OutgoingResponseInterface as Response;

class Login
{
    private $auth;

    private $login_service;

    private $twig;

    public function __construct(Twig_Environment $twig, Auth $auth, LoginService $login_service)
    {
        $this->auth = $auth;
        $this->login_service = $login_service;
        $this->twig = $twig;
    }

    public function get(Response $response)
    {
        $response->setHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->twig->render('login.html'));
    }

    public function post($request,Response $response)
    {
        $data = $request->getBodyParams();
        $this->login_service->login($this->auth, array(
            'username' => $data['username'],
            'password' => $data['password'],
        ));
        if ($this->auth->isValid()) {
            $response->setHeader('Content-Type', 'text/html');
            $response->setHeader('Location', '/admin');
        } else {
            $this->get($response);
        }
    }
}
