<?php

namespace App\WebSocket\Event;

class TakeCard extends AbstractEvent
{
    /**
     * @param array $user
     * @param int $card
     */
    public function __construct(array $user, $card)
    {
        parent::__construct('takeCard', ['user' => $user, 'card' => $card]);
    }
}
