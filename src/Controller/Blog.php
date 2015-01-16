<?php
namespace Controller;

use Zend\Escaper\Escaper;

class Blog
{
    public function browse($request, $response)
    {
        $response->setHeader('Content-Type', 'text/html');
        $response->getBody()->write("<p>I am from browse action</p>");
    }

    public function view($request, $response, $id)
    {
        $escaper = new Escaper();
        $response->setHeader('Content-Type', 'text/html');
        $response->getBody()->write("<p>I am viewing {$escaper->escapeHtml($id)} post.</p>");
    }
}
