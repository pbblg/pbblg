<?php

namespace App\WebSocket;

use Psr\Container\ContainerInterface;

class ClientFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $wsConfig = $container->get('config')['websocket'];
        return new Client(
            $wsConfig['client-secret'],
            $wsConfig['ws-url']
        );
    }
}
