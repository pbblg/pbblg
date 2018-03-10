<?php

namespace App\WebSocket\Event;

class NewGameCreated extends AbstractEvent
{
    /**
     * @param int $gameId
     */
    public function __construct(int $gameId)
    {
        parent::__construct('newGameCreated', ['gameId' => $gameId]);
    }
}
