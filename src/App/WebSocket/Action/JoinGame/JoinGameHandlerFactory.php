<?php

namespace App\WebSocket\Action\JoinGame;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class JoinGameHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new JoinGameHandler(
            $container->get('Game\Infrastructure\Repository'),
            $container->get('UsersInGames\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
