<?php

namespace App\WebSocket\Action\Ping;

use App\WebSocket\Action\ActionHandlerInterface;

class PingHandler implements ActionHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params)
    {
        return 'pong';
    }
}
