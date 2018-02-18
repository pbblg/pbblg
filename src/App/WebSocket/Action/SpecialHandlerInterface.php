<?php

namespace App\WebSocket\Action;


interface SpecialHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params);
}