<?php

namespace App\Console\Command\Migrations;

use Psr\Container\ContainerInterface;
use App\Command\Migration\InstallCommand;

class InstallActionFactory
{
    /**
     * @param ContainerInterface $container
     * @return InstallAction
     */
    public function __invoke(ContainerInterface $container)
    {
        $command = new InstallAction();
        $command->initializeCommand($container->get(InstallCommand::class));

        return $command;
    }
}
