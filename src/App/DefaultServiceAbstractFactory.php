<?php

namespace App;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class DefaultServiceAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $factoryName = $requestedName . "Factory";
        if (class_exists($factoryName)) {
            return true;
        }

        return class_exists($requestedName);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $factoryName = $requestedName . "Factory";
        if (class_exists($factoryName)) {
            $factory = new $factoryName();
            return $factory($container);
        }

        return new $requestedName();
    }
}