<?php

namespace App\WebSocket\Exception;

use InvalidArgumentException;

class InvalidRequestException extends InvalidArgumentException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, -32600, $previous);
    }
}
