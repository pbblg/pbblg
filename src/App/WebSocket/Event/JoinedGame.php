<?php

namespace App\WebSocket\Event;


class JoinedGame extends AbstractEvent
{
    /**
     * @param array $user
     */
    public function __construct(array $user)
    {
        parent::__construct('joinedGame', ['user' => $user]);
    }

}