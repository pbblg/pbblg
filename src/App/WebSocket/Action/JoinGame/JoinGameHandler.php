<?php

namespace App\WebSocket\Action\JoinGame;

use App\WebSocket\Action\ActionHandlerInterface;

class JoinGameHandler implements ActionHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params)
    {
        return 'JoinGame';
    }
}
