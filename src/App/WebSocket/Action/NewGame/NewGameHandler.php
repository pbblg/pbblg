<?php

namespace App\WebSocket\Action\NewGame;

use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\WebSocket\Event\NewGameCreated;

class NewGameHandler implements ActionHandlerInterface
{
    /**
     * @var Client
     */
    private $webSocketClient;

    public function __construct(Client $webSocketClient)
    {
        $this->webSocketClient = $webSocketClient;
    }

    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params)
    {
        $this->webSocketClient->send([], new NewGameCreated(123));
    }
}
