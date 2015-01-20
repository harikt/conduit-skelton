<?php
namespace Conduit\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Phly\Conduit\Middleware as BaseMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware extends BaseMiddleware
{
    private $auth;

    public function __construct(Auth $auth, ResumeService $resume_service)
    {
        parent::__construct();
        $resume_service->resume($auth);
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($this->auth->isValid()) {
            return $next();
        }
        $response = $response
            ->withStatus(401)
            ->withHeader('Location', '/login/');
        return $response;
    }
}
