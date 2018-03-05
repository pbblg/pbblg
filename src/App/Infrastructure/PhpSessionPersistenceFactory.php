<?php

namespace App\Infrastructure;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;

class PhpSessionPersistenceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $adapter = $container->get(Adapter::class);
        $tableGateway = new TableGateway('sessions', $adapter);
        $saveHandler  = new DbTableGateway($tableGateway, new DbTableGatewayOptions());

        return new PhpSessionPersistence(
            $saveHandler
        );
    }
}
