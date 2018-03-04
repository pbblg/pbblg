<?php

namespace App\Infrastructure;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\EventManager\SharedEventManager;
use Zend\EventManager\EventManager;
use Interop\Container\ContainerInterface;
use T4webInfrastructure\InMemoryRepository;
use T4webInfrastructure\Config;

/**
 * Create Service by template:
 *   ENTITY-NAME\Infrastructure\InMemoryRepository
 *
 * @package T4web\DomainModule\Infrastructure
 */
class InMemoryRepositoryAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -strlen('Infrastructure\InMemoryRepository')) == 'Infrastructure\InMemoryRepository';
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $namespace = strstr($requestedName, 'Infrastructure\InMemoryRepository', true);

        $namespaceParts = explode('\\', trim($namespace, "\\"));

        $entityName = $namespaceParts[0];
        /** @var Config $config */
        $config = $container->get("$entityName\\Infrastructure\\Config");
        $criteriaFactory = $container->get("$entityName\\Infrastructure\\CriteriaFactory");
        $entityFactory = $container->get("$entityName\\EntityFactory");

        $eventManager = new EventManager(
            $container->get(SharedEventManager::class)
        );
        $eventManager->addIdentifiers(["$entityName\\Infrastructure\\Repository"]);
        $collectionClass = $config->getCollectionClass($entityName);

        return new InMemoryRepository(
            $entityName,
            $collectionClass,
            $criteriaFactory,
            $entityFactory,
            $eventManager
        );
    }
}
