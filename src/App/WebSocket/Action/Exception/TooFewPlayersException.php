<?php

namespace App\WebSocket\Action\Exception;

use RuntimeException;

class TooFewPlayersException extends RuntimeException
{
    public function __construct($gameId)
    {
        parent::__construct(sprintf('Too few players for game #%s', $gameId));
    }
}
