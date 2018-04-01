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

    /**
     * @var array
     */
    public $wasSend = [];

    public function send(array $receivers, AbstractEvent $event)
    {
        $this->receivers = $receivers;
        $this->event = $event;

        $this->wasSend[] = [
            'receivers' => $receivers,
            'event' => $event,
        ];
        return true;
    }
}
