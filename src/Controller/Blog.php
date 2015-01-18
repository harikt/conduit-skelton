<?php
namespace Controller;

use Twig_Environment;

class Blog
{
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function browse($request, $response)
    {
        $response->setHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->twig->render('blog.browse.html'));
    }

    public function view($request, $response, $id)
    {
        $response->setHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->twig->render('blog.view.html', array('id' => $id)));
    }
}
