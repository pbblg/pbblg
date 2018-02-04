<?php

namespace App\WebSocket\Action\NewGame;

use App\WebSocket\Action\ActionHandlerInterface;

class NewGameHandler implements ActionHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params)
    {
        return 'NewGame';
    }
}
