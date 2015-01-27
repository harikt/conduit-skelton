<?php
namespace Controller;

use Twig_Environment;
use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Aura\Auth\Service\LoginService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

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

    public function get(ResponseInterface $response)
    {
        return $this->twig->render('login.html');
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getBodyParams();
        $this->login_service->login($this->auth, array(
            'username' => $data['username'],
            'password' => $data['password'],
        ));
        if ($this->auth->isValid()) {
            return $response
                ->withStatus(302)
                ->withHeader('Location', '/admin');
        }
        return $this->get($response);
    }
}
