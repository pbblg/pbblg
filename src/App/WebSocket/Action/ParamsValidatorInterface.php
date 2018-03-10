<?php

namespace App\WebSocket\Action;

interface ParamsValidatorInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function initialize(array $config);

    /**
     * @param array $params
     * @return bool
     */
    public function isValid(array $params);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @return array
     */
    public function getValid();
}
