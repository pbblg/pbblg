<?php

namespace App\WebSocket\Exception;

use InvalidArgumentException;

class InvalidParamsException extends InvalidArgumentException
{
    /**
     * @var array
     */
    private $errors = [];

    public function __construct(array $errors, \Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct('Bad params', -32602, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
