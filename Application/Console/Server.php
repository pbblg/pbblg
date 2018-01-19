<?php

namespace Game\Application\Console;

use Symfony\Component\Console\Application;
use Game\Application\AbstractServer;

class Server extends AbstractServer
{
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
        $app = $this->getApp();
        $console = new Application('pbblg');

        $commands = [
//            MigrateCommand::class,
//            InfoCommand::class,
//            CacheClearCommand::class
        ];

        foreach ($commands as $command) {
            $console->add($app->getService($command));
        }

        return $console;
    }
}