<?php

namespace App\WebSocket\Command;

use Psr\Container\ContainerInterface;
use App\WebSocket\Client;
use App\Domain\AccessToken\Generator;

class LoginCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LoginCommand(
            $container->get(Generator::class),
            $container->get(Client::class),
            $container->get('User\Infrastructure\Repository')
        );
    }
}
