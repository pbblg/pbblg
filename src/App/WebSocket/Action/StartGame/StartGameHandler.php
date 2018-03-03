<?php

namespace App\WebSocket\Action\StartGame;

use App\WebSocket\Action\ActionHandlerInterface;

class StartGameHandler implements ActionHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params)
    {
        return 'StartGame';
    }
}
