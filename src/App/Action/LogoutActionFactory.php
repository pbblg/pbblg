<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;

class LogoutActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LogoutAction(
            $container->get('AccessToken\Infrastructure\Repository'),
            $container->get('User\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
