<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Command\Migration\ListCommandHandler;

class MigrateCommand extends Command
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
     * @var ListCommandHandler
     */
    private $listCommandHandler;

    public function initializeCommands(ListCommandHandler $listCommandHandler)
    {
        $this->listCommandHandler = $listCommandHandler;
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

        if ($this->input->getOption('run')) {
            return $this->executeRun();
        }

        return $this->executeList();
    }

    private function executeRun()
    {
        $this->info('Run migration ...');

        $this->info('Done.');
    }

    private function executeList()
    {
        $this->info('Available migrations:');

        $versions = $this->listCommandHandler->handle();

        $output = [];
        foreach ($versions as $version) {
            $output[] = $version['version'] . ' - ' . $version['description'];
        }

        $io = new SymfonyStyle($this->input, $this->output);
        $io->listing($output);
    }

    protected function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription("Run pbblg migration")
            ->addOption(
                'list',
                'l',
                InputOption::VALUE_NONE,
                'List available migrations'
            )
            ->addOption(
                'run',
                'r',
                InputOption::VALUE_NONE,
                'Apply migration'
            );
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
