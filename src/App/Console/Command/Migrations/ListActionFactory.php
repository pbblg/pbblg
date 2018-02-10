<?php

namespace App\Console\Command\Migrations;

use Psr\Container\ContainerInterface;
use App\Command\Migration\ListCommand;

class ListActionFactory
{
    /**
     * @param ContainerInterface $container
     * @return ListAction
     */
    public function __invoke(ContainerInterface $container)
    {
        $command = new ListAction();
        $command->initializeCommand($container->get(ListCommand::class));

        return $command;
    }
}
