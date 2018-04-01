<?php

namespace App\WebSocket\Event;

class UserGotCard extends AbstractEvent
{
    /**
     * @param array $user
     */
    public function __construct(array $user)
    {
        parent::__construct('userGotCard', ['user' => $user]);
    }
}
