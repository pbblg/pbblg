<?php

use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Router\ZendRouter;

return [
    'dependencies' => [
        'invokables' => [
            RouterInterface::class => ZendRouter::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'allowed_methods' => ['GET'],
            'middleware' => [
                App\Action\HomePageAction::class
            ],
        ],
        [
            'name' => 'login',
            'path' => '/login',
            'allowed_methods' => ['GET', 'POST'],
            'middleware' => [
                App\Action\LoginAction::class,
                \Zend\Expressive\Authentication\AuthenticationMiddleware::class
            ],
        ],
        [
            'name' => 'logout',
            'path' => '/logout',
            'allowed_methods' => ['GET'],
            'middleware' => [
                App\Action\LogoutAction::class,
            ],
        ],
        [
            'name' => 'register',
            'path' => '/register',
            'allowed_methods' => ['GET', 'POST'],
            'middleware' => [
                App\Action\Register\RegisterAction::class,
                \Zend\Expressive\Authentication\AuthenticationMiddleware::class
            ],
        ],
        [
            'name' => 'api.ping',
            'path' => '/api/ping',
            'middleware' => [
                App\Action\PingAction::class
            ],
        ],
        [
            'name' => 'admin',
            'path' => '/admin',
            'allowed_methods' => ['GET'],
            'middleware' => [
                App\Action\Admin\DashboardAction::class,
                \Zend\Expressive\Authentication\AuthenticationMiddleware::class
            ],
        ],
        [
            'name' => 'admin-games-list',
            'path' => '/admin/games',
            'allowed_methods' => ['GET'],
            'middleware' => [
                App\Action\Admin\Games\ListAction::class,
                \Zend\Expressive\Authentication\AuthenticationMiddleware::class
            ],
        ],
        [
            'name' => 'admin-users-list',
            'path' => '/admin/users',
            'allowed_methods' => ['GET'],
            'middleware' => [
                App\Action\Admin\Users\ListAction::class,
                \Zend\Expressive\Authentication\AuthenticationMiddleware::class
            ],
        ],
    ],
];
