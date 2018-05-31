<?php

namespace App\WebSocket\Event;

use App\Domain\User\User;

class Authenticated extends AbstractEvent
{
    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct('authenticated', $user->extract());
    }
}
