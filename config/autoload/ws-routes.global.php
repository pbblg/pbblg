<?php

use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Router\ZendRouter;

return [
    'routes' => [
        'invokables' => [
            RouterInterface::class => ZendRouter::class,
        ],
    ],
];
