<?php

namespace App\WebSocket\Middleware;

use Psr\Container\ContainerInterface;

class AuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AuthMiddleware(
            $container->get('AccessToken\Infrastructure\Repository'),
            $container->get('User\Infrastructure\Repository')
        );
    }
}
