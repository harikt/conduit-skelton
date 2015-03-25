<?php
namespace Response;

interface PayloadInterface
{
    public function getView();

    public function setView($view);

    public function getLayout();

    public function setLayout($layout);

    public function getData();

    public function setData($data);

    public function getAvailable();

    public function setAvailable($available);
}
