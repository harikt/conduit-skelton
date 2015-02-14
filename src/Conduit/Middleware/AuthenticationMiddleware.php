<?php
namespace Conduit\Middleware;

use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Authentication\AuthenticationService;

class AuthenticationMiddleware implements MiddlewareInterface
{
    private $auth;

    public function __construct(AuthenticationService $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($this->auth->hasIdentity()) {
            return $next($request, $response);
        }
        return $response
            ->withStatus(401)
            ->withHeader('Location', '/login');
    }
}
