<?php

namespace App\Domain\User;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class RepositoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Repository(
            new TableGateway('users', $container->get(Adapter::class))
        );
    }
}
