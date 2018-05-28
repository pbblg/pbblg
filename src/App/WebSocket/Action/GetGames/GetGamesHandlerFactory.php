<?php

namespace App\WebSocket\Action\GetGames;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class GetGamesHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new GetGamesHandler(
            $container->get('Game\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
