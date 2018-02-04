<?php

namespace App\WebSocket\Middleware;

use Psr\Container\ContainerInterface;

class DispatchMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return DispatchMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        return new DispatchMiddleware($container);
    }
}
