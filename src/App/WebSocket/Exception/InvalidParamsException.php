<?php

namespace App\WebSocket\Exception;

use InvalidArgumentException;

class InvalidParamsException extends InvalidArgumentException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, -32602, $previous);
    }
}