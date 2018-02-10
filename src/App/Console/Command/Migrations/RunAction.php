<?php

namespace App\Console\Command\Migrations;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use App\Command\Migration\RunCommand;
use App\Command\Migration\RunCommandContext;

class RunAction extends Command
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
     * @var RunCommand
     */
    private $runCommandHandler;

    public function initializeCommand(RunCommand $runCommandHandler)
    {
        $this->runCommandHandler = $runCommandHandler;
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

        $this->info('Run migration..');

        $this->runCommandHandler->handle(new RunCommandContext($input->getArgument('version')));

        $this->info("\nDone.");
    }

    protected function configure()
    {
        $this
            ->setName('migrations:run')
            ->setDescription("Run migration")
            ->setDefinition(array(
                new InputArgument('version', InputArgument::REQUIRED),
            ));

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
