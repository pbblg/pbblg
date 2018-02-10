<?php

namespace App\Console\Command\Migrations;

use Psr\Container\ContainerInterface;
use App\Command\Migration\RunCommand;

class RunActionFactory
{
    /**
     * @param ContainerInterface $container
     * @return RunAction
     */
    public function __invoke(ContainerInterface $container)
    {
        $command = new RunAction();
        $command->initializeCommand($container->get(RunCommand::class));

        return $command;
    }
}
