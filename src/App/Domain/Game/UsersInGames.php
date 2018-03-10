<?php
/**
 * Created by PhpStorm.
 * User: maxgu
 * Date: 08.03.18
 * Time: 9:03
 */

namespace App\Domain\Game;

use T4webDomain\Entity;

class UsersInGames extends Entity
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var int
     */
    protected $gameId;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }
}
