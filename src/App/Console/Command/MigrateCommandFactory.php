<?php

namespace App\Console\Command;

use Psr\Container\ContainerInterface;
use App\Command\Migration\ListCommandHandler;

class MigrateCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return MigrateCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        $command = new MigrateCommand();
        $command->initializeCommands($container->get(ListCommandHandler::class));

        return $command;
    }
}
