<?php

namespace App\WebSocket\Event;

class UserLoggedIn extends AbstractEvent
{
    /**
     * @param array $user
     */
    public function __construct(array $user)
    {
        parent::__construct('userLoggedIn', $user);
    }
}
