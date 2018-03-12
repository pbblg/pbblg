<?php

namespace App\Domain\Game;

class GameStatus
{
    const STATUS_OPEN = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_ENDED = 3;

    public static $statusArray = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_IN_PROGRESS => 'In progress',
        self::STATUS_ENDED => 'Ended',
    ];

    public static function getById($scopeId)
    {
        if (isset(self::$statusArray[$scopeId])) {
            return self::$statusArray[$scopeId];
        }
    }

    public static function getAll()
    {
        return self::$statusArray;
    }
}
