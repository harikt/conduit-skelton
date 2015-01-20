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
        $available = array(
            'application/json' => 'json',
            'text/html' => 'html',
        );
        $posts = array(
            array(
                'title' => 'First post',
                'content' => 'First post body',
                'author' => 'Hari KT',
            ),
            array(
                'title' => 'Second post',
                'content' => 'Second post body',
                'author' => 'Paul M Jones',
            ),
        );
        $format = isset($available[$response->getHeader('Content-Type')]) ? $available[$response->getHeader('Content-Type')] : 'html';
        return $this->twig->render('blog.browse.' . $format, array('posts' => $posts));
    }

    public function view($request, $response, $id)
    {
        return $this->twig->render('blog.view.html', array('id' => $id));
    }
}
