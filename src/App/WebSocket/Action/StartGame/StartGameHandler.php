<?php

namespace App\WebSocket\Action\StartGame;

use App\WebSocket\Action\ActionHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class StartGameHandler implements ActionHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        return 'StartGame';
    }
}
