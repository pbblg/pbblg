<?php

namespace App\WebSocket\Event;

class PlayerLeftTheGame extends AbstractEvent
{
    /**
     * @param array $user
     */
    public function __construct(array $user)
    {
        parent::__construct('playerLeftTheGame', ['user' => $user]);
    }
}
