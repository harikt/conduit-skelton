<?php
namespace Form;

class ContactForm extends AbstractForm
{
    protected function init()
    {
        $this->filter->validate('name')->is('strlenMin', 3);
        $this->filter->sanitize('name')->to('string');
        $this->filter->validate('email')->is('email');
        $this->filter->validate('subject')->is('strlenMin', 6);
        $this->filter->validate('subject')->is('alnum');
        $this->filter->validate('message')->is('strlenMin', 6);

        $this->filter->useFieldMessage('name', 'Enter your name.');
        $this->filter->useFieldMessage('email', 'Please give a valid email address.');
        $this->filter->useFieldMessage('subject', 'Please give a subject.');
        $this->filter->useFieldMessage('message', 'Please type your matter.');
    }
}
