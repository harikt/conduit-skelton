<?php
namespace Form;

use Zend\InputFilter\InputFilter;

abstract class AbstractForm
{
    protected $inputFilter;

    public function __construct()
    {
        $this->inputFilter = new InputFilter();
        $this->init();
    }

    abstract protected function init();

    public function isValid(array $data)
    {
        return $this->inputFilter
            ->setData($data)
            ->isValid();
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}
