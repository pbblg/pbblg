<?php

namespace App\WebSocket\Exception;

use InvalidArgumentException;

class MethodNotFoundException extends InvalidArgumentException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, -32601, $previous);
    }
}