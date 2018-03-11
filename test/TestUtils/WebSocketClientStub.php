<?php

namespace TestUtils;

use App\WebSocket\Client;
use App\WebSocket\Event\AbstractEvent;

class WebSocketClientStub extends Client
{
    /**
     * @var array
     */
    public $receivers;

    /**
     * @var AbstractEvent
     */
    public $event;

    public function send($receivers, AbstractEvent $event)
    {
        $this->receivers = $receivers;
        $this->event = $event;
        return true;
    }
}