<?php

namespace App\WebSocket\Action\JoinGame;

use Psr\Container\ContainerInterface;
use App\WebSocket\Command\JoinGameCommand;

class JoinGameHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new JoinGameHandler(
            $container->get(JoinGameCommand::class)
        );
    }
}
