<?php

namespace App\WebSocket\Action\Ping;

use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Action\ActionHandlerInterface;

class PingHandler implements ActionHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        return 'pong';
    }
}
