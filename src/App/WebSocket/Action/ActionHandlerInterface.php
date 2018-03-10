<?php

namespace App\WebSocket\Action;

use Psr\Http\Message\ServerRequestInterface;

interface ActionHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request);
}
