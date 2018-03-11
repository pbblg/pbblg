<?php

namespace App\Action\Admin\Games;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ListAction(
            $container->get('Game\Infrastructure\Repository'),
            $container->get('User\Infrastructure\Repository'),
            $container->get('UsersInGames\Infrastructure\Repository'),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
