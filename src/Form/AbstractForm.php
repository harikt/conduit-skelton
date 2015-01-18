<?php
namespace Form;

use Aura\Filter\FilterFactory;
use Aura\Filter\Filter;

abstract class AbstractForm
{
    protected $filter;

    public function __construct(Filter $filter = null)
    {
        if (! $filter) {
            $filter_factory = new FilterFactory();
            $filter = $filter_factory->newFilter();
        }
        $this->filter = $filter;

        $this->init();
    }

    abstract protected function init();

    public function isValid($subject)
    {
        return $this->filter->apply($subject);
    }

    public function getFilter()
    {
        return $this->filter;
    }
}
