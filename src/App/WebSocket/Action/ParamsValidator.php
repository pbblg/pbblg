<?php

namespace App\WebSocket\Action;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFilterFactory;

class ParamsValidator implements ParamsValidatorInterface
{
    /**
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * @var array
     */
    protected $config = [];

    public function __construct()
    {
        $this->inputFilter = new InputFilter();
    }

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        foreach ($config as $name => $inputConfig) {
            $this->config[$name] = $inputConfig;
            if (!array_key_exists('name', $this->config[$name])) {
                $this->config[$name]['name'] = $name;
            }
        };
        $factory = new InputFilterFactory();

        $this->inputFilter = $factory->createInputFilter($this->config);
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
