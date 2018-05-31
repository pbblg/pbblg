<?php

namespace App\WebSocket\Action\Exception;

use RuntimeException;

class UserNotInGameException extends RuntimeException
{
    public function __construct($userId, $gameId)
    {
        parent::__construct(sprintf('User #%s is not in the game #%s', $userId, $gameId));
    }
}
