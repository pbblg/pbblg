<?php

namespace App\Action;

use Zend\InputFilter\InputFilter;

class LoginInputFilter extends InputFilter
{
    /**
     * @var array
     */
    private $messages;

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
    }
}
