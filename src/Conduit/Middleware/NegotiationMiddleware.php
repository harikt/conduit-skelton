<?php
namespace Conduit\Middleware;

use Phly\Conduit\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Accept\Accept;

class NegotiationMiddleware implements MiddlewareInterface
{
    private $accept;

    private $available = array(
        'application/json',
        'text/html',
        'text/plain',
    );

    public function __construct(Accept $accept, $available = array())
    {
        $this->accept = $accept;
        $this->available = array_merge($this->available, $available);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $media = $this->accept->negotiateMedia($this->available);
        if ($media) {
            $response = $response->withHeader('Content-Type', $media->getValue());
        }
        return $next($request, $response);
    }
}
