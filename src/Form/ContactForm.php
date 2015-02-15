<?php
namespace Form;
use Zend\InputFilter\Input;
use Zend\Validator;

class ContactForm extends AbstractForm
{
    protected function init()
    {
        $name = new Input('name');
        $name->getValidatorChain()
                 ->attach(new Validator\StringLength(3));

        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new Validator\EmailAddress());

        $subject = new Input('subject');
        $subject->getValidatorChain()
                 ->attach(new Validator\StringLength(6));

        $message = new Input('message');
        $message->getValidatorChain()
                 ->attach(new Validator\StringLength(10));

        $this->getInputFilter()->add($name)
            ->add($email)
            ->add($subject)
            ->add($message);
    }
}
