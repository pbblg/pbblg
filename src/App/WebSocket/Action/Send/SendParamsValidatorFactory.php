<?php

namespace App\WebSocket\Action\Send;

use Psr\Container\ContainerInterface;

class SendParamsValidatorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $wsConfig = $container->get('config')['websocket'];
        return new SendParamsValidator($wsConfig['client-secret']);
    }
}
