<?php

namespace App\Command\Migration;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class RunCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return RunCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(Adapter::class);

        return new RunCommand(
            $container,
            new TableGateway('migrations', $dbAdapter)
        );
    }
}
