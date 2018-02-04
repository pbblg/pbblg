<?php

namespace App\WebSocket\Middleware;

use Psr\Container\ContainerInterface;

class ParamsValidatorMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return ParamsValidatorMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ParamsValidatorMiddleware($container);
    }
}
