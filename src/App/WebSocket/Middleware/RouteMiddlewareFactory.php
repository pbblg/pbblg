<?php

namespace App\WebSocket\Middleware;

use ArrayObject;
use Psr\Container\ContainerInterface;
use App\WebSocket\Router\Router;

class RouteMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return RouteMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $container = require 'config/ws-container.php';
        $config = $container->has('config') ? $container->get('config') : [];
        $config = $config instanceof ArrayObject ? $config->getArrayCopy() : $config;

        $router = new Router($config['routes']);

        return new RouteMiddleware($router);
    }
}
