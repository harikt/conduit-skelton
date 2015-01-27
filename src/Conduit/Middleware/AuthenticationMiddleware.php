<?php
namespace Conduit\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    private $auth;

    public function __construct(Auth $auth, ResumeService $resume_service)
    {
        $resume_service->resume($auth);
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($this->auth->isValid()) {
            return $next($request, $response);
        }
        return $response
            ->withStatus(401)
            ->withHeader('Location', '/login');
    }
}
