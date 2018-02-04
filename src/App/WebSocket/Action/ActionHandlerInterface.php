<?php

namespace App\WebSocket\Action;


interface ActionHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params);
}