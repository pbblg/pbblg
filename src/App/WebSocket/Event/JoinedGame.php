<?php

namespace App\WebSocket\Event;

use App\Domain\Game\ViewModel\Game as GameViewModel;

class JoinedGame extends AbstractEvent
{
    /**
     * @param array $user
     * @param GameViewModel $game
     */
    public function __construct(array $user, GameViewModel $game)
    {
        parent::__construct('joinedGame', ['user' => $user, 'game' => $game->extract()]);
    }
}
