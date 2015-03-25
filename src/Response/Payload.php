<?php
namespace Response;

class Payload implements PayloadInterface
{
    protected $view;

    protected $layout;

    protected $data;

    protected $available;

    public function __construct(
        array $data = array(),
        $view = null,
        $layout = null,
        $available = array(
            'text/html' => '.html',
        )
    ) {
        $this->data = $data;
        $this->view = $view;
        $this->layout = $layout;
        $this->available = $available;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setAvailable($available)
    {
        $this->available = $available;
    }

    public function getAvailable()
    {
        return $this->available;
    }
}
