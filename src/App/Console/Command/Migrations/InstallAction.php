<?php

namespace App\Console\Command\Migrations;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Command\Migration\InstallCommand;

class InstallAction extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var InstallCommand
     */
    private $installCommandHandler;

    public function initializeCommand(InstallCommand $installCommandHandler)
    {
        $this->installCommandHandler = $installCommandHandler;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->info('Init migrations..');

        if (!$this->installCommandHandler->handle()) {
            $this->output->writeln("  <comment>Already installed</comment>");
        }

        $this->info("Done.");
    }

    protected function configure()
    {
        $this
            ->setName('migrations:install')
            ->setDescription("Install and initialization migration system");

    }

    /**
     * Send an info string to the user.
     *
     * @param string $string
     */
    protected function info($string)
    {
        $this->output->writeln("<info>$string</info>");
    }
}
