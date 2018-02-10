<?php

namespace App\Command\Migration;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class ListCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return ListCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new ListCommand(
            $config['migration'],
            new TableGateway('migrations', $container->get(Adapter::class))
        );
    }
}
