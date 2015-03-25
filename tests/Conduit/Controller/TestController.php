<?php
namespace Conduit\Controller;

use Psr\Http\Message\ResponseInterface;
use Response\Payload;

class TestController
{
    public function returnString()
    {
        return "Hello from return string method";
    }

    public function returnPayload()
    {
        return new Payload(array('name' => 'Hari KT'), 'greet');
    }

    public function returnPayloadWithAvailable()
    {
        $available = array(
            'text/html' => '.html',
            'application/json' => '.json',
        );
        return new Payload(array('name' => 'Hari KT'), 'greet', null, $available);
    }

    public function returnResponse(ResponseInterface $response)
    {
        return $response->withStatus(200)
                    ->withHeader('Content-Type', 'text/html')
                    ->write("returns response");
    }
}
