<?php

namespace App\Command\Register;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class RegisterCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return RegisterCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        return new RegisterCommand(
            new TableGateway('users', $container->get(Adapter::class))
        );
    }
}
