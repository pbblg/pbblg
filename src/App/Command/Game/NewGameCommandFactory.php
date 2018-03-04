<?php

namespace App\Command\Game;

use Psr\Container\ContainerInterface;

class NewGameCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new NewGameCommand(
            $container->get('Game\Infrastructure\Repository')
        );
    }
}
