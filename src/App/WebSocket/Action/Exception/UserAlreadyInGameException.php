<?php

namespace App\WebSocket\Action\Exception;

use RuntimeException;

class UserAlreadyInGameException extends RuntimeException
{
    public function __construct($gameId)
    {
        parent::__construct(sprintf('User already in this game #%s', $gameId));
    }
}
