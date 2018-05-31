<?php

namespace App\WebSocket\Event;

class UserLoggedOut extends AbstractEvent
{
    /**
     * @param array $user
     */
    public function __construct(array $user)
    {
        parent::__construct('userLoggedOut', $user);
    }
}
