<?php

namespace App\Action\Register;

use Zend\InputFilter\InputFilter;

class RegisterInputFilter extends InputFilter
{
    /**
     * @var array
     */
    private $messages = [];

    public function __construct()
    {
        $this->add(
            [
                'name' => 'username',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'username'
        );

        $this->add(
            [
                'name' => 'password',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'password'
        );

        $this->add(
            [
                'name' => 'password-again',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'password-again'
        );
    }

    public function isValid($context = null)
    {
        $isValid = parent::isValid($context);
        $data = $this->getValues();
        if ($data['password'] != $data['password-again']) {
            $this->messages['password-again'] = ['notMath' => 'Passwords did not match'];
            $isValid = false;
        }

        return $isValid;
    }

    public function getMessages()
    {
        return array_merge(parent::getMessages(), $this->messages);
    }
}
