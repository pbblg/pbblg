<?php

namespace App\WebSocket\Action\GetOnlineUsers;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class GetOnlineUsersHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new GetOnlineUsersHandler(
            $container->get('User\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
