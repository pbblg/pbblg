<?php

namespace App\Domain\AccessToken;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class RepositoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Repository(
            new TableGateway('access_tokens', $container->get(Adapter::class))
        );
    }
}
