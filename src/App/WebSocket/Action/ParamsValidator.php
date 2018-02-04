<?php

namespace App\WebSocket\Action;

use Zend\InputFilter\InputFilter;

class ParamsValidator implements ParamsValidatorInterface
{
    /**
     * @var InputFilter
     */
    protected $inputFilter;

    public function __construct()
    {
        $this->inputFilter = new InputFilter();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data)
    {
        if ($data === null) {
            $data = [];
        }
        $this->inputFilter->setData($data);

        return $this->inputFilter->isValid($data);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->inputFilter->getMessages();
    }

    /**
     * @return array
     */
    public function getValid()
    {
        $validInput = $this->inputFilter->getValidInput();

        $valid = [];
        foreach ($validInput as $name => $input) {
            $value = $input->getValue();
            $empty = ($value === null || $value === '' || $value === []);
            if (!$empty) {
                $valid[$name] = $input->getValue();
            }
        }

        return $valid;
    }
}
