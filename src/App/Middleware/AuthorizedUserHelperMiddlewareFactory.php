<?php

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Zend\View\HelperPluginManager;
use App\ViewHelper\IsAuthorizedHelper;

class AuthorizedUserHelperMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $helperManager = $container->get(HelperPluginManager::class);

        return new AuthorizedUserHelperMiddleware($helperManager->get(IsAuthorizedHelper::class));
    }
}
