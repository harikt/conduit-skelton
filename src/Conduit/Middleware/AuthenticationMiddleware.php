<?php
namespace Conduit\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Psr\Http\Message\IncomingRequestInterface as Request;
use Psr\Http\Message\OutgoingResponseInterface as Response;

class AuthenticationMiddleware
{
    private $auth;

    public function __construct(Auth $auth, ResumeService $resume_service)
    {
        $resume_service->resume($auth);
        $this->auth = $auth;
    }

    public function handle(Request $request, Response $response, callable $next = null)
    {
        if ($this->auth->isValid()) {
            $next();
        } else {
            $response->setStatus(401);
            $response->setHeader('Location', '/login');
        }
    }
}
