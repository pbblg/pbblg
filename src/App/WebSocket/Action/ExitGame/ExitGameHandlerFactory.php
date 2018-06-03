<?php

namespace App\WebSocket\Action\ExitGame;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class ExitGameHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ExitGameHandler(
            $container->get('Game\Infrastructure\Repository'),
            $container->get('UsersInGames\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
