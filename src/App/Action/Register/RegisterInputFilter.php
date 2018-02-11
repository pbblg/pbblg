<?php

namespace App\Action\Register;

use Zend\InputFilter\InputFilter;

class RegisterInputFilter extends InputFilter
{
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
}
