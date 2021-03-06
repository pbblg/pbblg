<?php

namespace App\Infrastructure;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\SequenceFeature;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\EventManager\SharedEventManager;
use Zend\EventManager\EventManager;
use Interop\Container\ContainerInterface;
use T4webInfrastructure\Repository;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   ENTITY-NAME\Infrastructure\Repository
 */
class RepositoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\Repository')) == 'Infrastructure\Repository';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Infrastructure\Repository', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        $entityName = $namespaceParts[0];
        /** @var Config $config */
        $config = $container->get("$entityName\\Infrastructure\\Config");
        $criteriaFactory = $container->get("$entityName\\Infrastructure\\CriteriaFactory");
        $mapper = $container->get("$entityName\\Infrastructure\\Mapper");
        $entityFactory = $container->get("$entityName\\EntityFactory");

        $features = [];
        $tableSequence = $config->getSequence($entityName);
        $tablePrimaryKey = $config->getPrimaryKey($entityName);
        if (!empty($tableSequence) && !empty($tablePrimaryKey)) {
            $features[] = new SequenceFeature($tablePrimaryKey, $tableSequence);
        }

        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $tableGateway = new TableGateway($config->getTable($entityName), $dbAdapter, $features);

        $eventManager = new EventManager(
            $container->get(SharedEventManager::class)
        );
        $eventManager->addIdentifiers([$requestedName]);

        return new Repository(
            $entityName,
            $criteriaFactory,
            $tableGateway,
            $mapper,
            $entityFactory,
            $eventManager,
            $tablePrimaryKey
        );
    }
}
