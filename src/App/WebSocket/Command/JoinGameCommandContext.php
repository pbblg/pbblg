<?php

namespace App\WebSocket\Command;

use App\Domain\User\User;

class JoinGameCommandContext
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $gameId;

    /**
     * @param User $user
     * @param int $gameId
     */
    public function __construct(User $user, int $gameId)
    {
        $this->user = $user;
        $this->gameId = $gameId;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }
}
