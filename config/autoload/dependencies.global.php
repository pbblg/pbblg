<?php

use Zend\Expressive\Application;
use Zend\Expressive\Container;
use Zend\Expressive\Delegate;
use Zend\Expressive\Helper;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
        ],
        'abstract_factories' => [
            \App\DefaultServiceAbstractFactory::class,

            \App\EntityFactoryAbstractFactory::class,
            \App\Infrastructure\RepositoryAbstractFactory::class,
            \App\Infrastructure\InMemoryRepositoryAbstractFactory::class,
            \App\Infrastructure\MapperAbstractFactory::class,
            \App\Infrastructure\CriteriaFactoryAbstractFactory::class,
            \App\Infrastructure\ConfigAbstractFactory::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            Application::class                => Container\ApplicationFactory::class,
            Delegate\NotFoundDelegate::class  => Container\NotFoundDelegateFactory::class,
            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelper::class           => Helper\UrlHelperFactory::class,
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,

            Zend\Db\Adapter\Adapter::class => Zend\Db\Adapter\AdapterServiceFactory::class,
        ],
        'shared' => [
            \App\WebSocket\Action\ParamsValidator::class => false
        ]
    ],
];
