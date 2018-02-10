<?php

namespace App\Command\Migration;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;

class InstallCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return InstallCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        return new InstallCommand(
            $container->get(Adapter::class)
        );
    }
}
