<?php
namespace Controller;

use Psr\Http\Message\ResponseInterface;
use Zend\Authentication\AuthenticationService;

class Logout
{
    private $auth;

    public function __construct(AuthenticationService $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(ResponseInterface $response)
    {
        $this->auth->clearIdentity();
        return $response
            ->withStatus(302)
            ->withHeader('Location', '/login');
    }
}
