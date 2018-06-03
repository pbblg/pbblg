<?php

namespace App\WebSocket\Command;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class JoinGameCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new JoinGameCommand(
            $container->get('Game\Infrastructure\Repository'),
            $container->get('UsersInGames\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
