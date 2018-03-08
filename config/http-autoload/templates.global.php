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
        'paths' => [
            'block' => 'templates/block',
            'block-admin' => 'templates/block/admin',
            'app-admin' => 'templates/app/admin',
            'app-admin-games' => 'templates/app/admin/games',
        ],
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
