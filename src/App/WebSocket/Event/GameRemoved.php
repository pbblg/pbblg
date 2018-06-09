<?php

namespace App\WebSocket\Event;

use App\Domain\Game\ViewModel\Game as GameViewModel;

class GameRemoved extends AbstractEvent
{
    /**
     * @param GameViewModel $game
     */
    public function __construct(GameViewModel $game)
    {
        parent::__construct('gameRemoved', ['game' => $game->extract()]);
    }
}
