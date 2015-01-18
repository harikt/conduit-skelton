<?php
namespace Conduit\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService;
use Phly\Conduit\Middleware as BaseMiddleware;
use Psr\Http\Message\IncomingRequestInterface as Request;
use Psr\Http\Message\OutgoingResponseInterface as Response;

class AuthenticationMiddleware extends BaseMiddleware
{
    private $auth;

    public function __construct(Auth $auth, ResumeService $resume_service)
    {
        parent::__construct();
        $resume_service->resume($auth);
        $this->auth = $auth;
    }

    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if ($this->auth->isValid()) {
            $next();
        } else {
            $response->setStatus(401);
            $response->setHeader('Location', '/login');
        }
    }
}
