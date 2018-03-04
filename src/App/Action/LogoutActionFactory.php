<?php

namespace App\Action;

use Psr\Container\ContainerInterface;

class LogoutActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LogoutAction(
            $container->get('AccessToken\Infrastructure\Repository')
        );
    }
}
