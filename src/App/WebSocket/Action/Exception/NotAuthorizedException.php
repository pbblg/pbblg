<?php

namespace App\WebSocket\Action\Exception;

use RuntimeException;

class NotAuthorizedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Only for authorized, you are not authorized.');
    }
}