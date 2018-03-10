<?php

namespace App\WebSocket\Action\Exception;

use RuntimeException;

class GameNotExistsException extends RuntimeException
{
    public function __construct($gameId)
    {
        parent::__construct(sprintf('Game #%s not exists', $gameId));
    }
}
