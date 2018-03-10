<?php

namespace App\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

class Server
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen()
    {
        $console = $this->getConsoleApplication();

        exit($console->run());
    }

    /**
     * @return Application
     */
    protected function getConsoleApplication()
    {
        $console = new Application('pbblg');

        $commands = [
            Command\Migrations\InstallAction::class,
            Command\Migrations\ListAction::class,
            Command\Migrations\RunAction::class,
        ];

        foreach ($commands as $command) {
            $console->add($this->container->get($command));
        }

        return $console;
    }
}
