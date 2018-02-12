<?php

use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\ZendView\HelperPluginManagerFactory;
use Zend\Expressive\ZendView\ZendViewRendererFactory;
use Zend\View\HelperPluginManager;

return [
    'dependencies' => [
        'factories' => [
            TemplateRendererInterface::class => ZendViewRendererFactory::class,
            HelperPluginManager::class => HelperPluginManagerFactory::class,
        ],
    ],

    'templates' => [
        'layout' => 'layout::default',
    ],

    'view_helpers' => [
        'aliases' => [
            'isAuthorized' => App\ViewHelper\IsAuthorizedHelper::class,
        ],
        'invokables' => [
            App\ViewHelper\IsAuthorizedHelper::class => App\ViewHelper\IsAuthorizedHelper::class,
        ],
    ],
];
