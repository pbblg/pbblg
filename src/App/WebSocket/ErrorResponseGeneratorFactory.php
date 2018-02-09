<?php

namespace App\WebSocket;

use Psr\Container\ContainerInterface;

class ErrorResponseGeneratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ErrorResponseGenerator
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        $debug = isset($config['debug']) ? $config['debug'] : false;

        return new ErrorResponseGenerator($debug);
    }
}
