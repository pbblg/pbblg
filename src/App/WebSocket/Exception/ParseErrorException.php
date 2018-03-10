<?php

namespace App\WebSocket\Exception;

use InvalidArgumentException;

class ParseErrorException extends InvalidArgumentException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, -32700, $previous);
    }
}
