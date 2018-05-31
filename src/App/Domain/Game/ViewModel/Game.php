<?php

namespace App\Domain\Game\ViewModel;

use App\Domain;

class Game
{
    /**
     * @var Domain\Game\Game
     */
    private $game;

    public function __construct(Domain\Game\Game $game)
    {
        $this->game = $game;
    }

    public function extract()
    {
        return [
            'id' => $this->game->getId(),
            'status' => $this->game->getStatus(),
            'ownerId' => $this->game->getOwnerId(),
            'created' => $this->game->getCreatedDt(),
        ];
    }
}
