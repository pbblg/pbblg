<?php

namespace App\WebSocket\Exception;

use RuntimeException;

class InternalErrorException extends RuntimeException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, -32603, $previous);
    }
}
