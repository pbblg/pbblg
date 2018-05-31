<?php

namespace App\WebSocket\Action\StartGame;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class StartGameHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new StartGameHandler(
            $container->get('Game\Infrastructure\Repository'),
            $container->get('UsersInGames\Infrastructure\Repository'),
            $container->get('User\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
