<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class LifeCommand
 * @sample php bin/console app:life --days=5
 * @sample php bin/console app:life --all
 * @package App\Command
 */
class LifeCommand extends Command
{
    protected static $defaultName = 'app:life';
    protected static $defaultDescription = 'Execute the life day for sociey array';

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument(
                'data',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Incoming society array. 
                Input the cells array (separate elements with a comma "," and rows with a semicolon ";").
                Default is 0,X,0; 0,0,X; X,X,X;'
            )
            ->addOption(
                'days',
                null,
                InputOption::VALUE_OPTIONAL,
                'Run life for N iterations (N days)'
            )
            ->addOption(
                'all',
                null,
                InputOption::VALUE_OPTIONAL,
                'Run all life days until the stable state'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $data = [
            ['.', '*', '.'],
            ['.', '.', '*'],
            ['*', '*', '*'],
        ];

        $days = $input->getOption('days');
        $days = (int)$days;
        $days = $days >= 1 ? $days : 1;
        $io->note('You requested to execute ' . $days . ' iterations');
        for ($i = 0; $i < $days; $i++) {
            $output->writeln('');
            $output->writeln('Day ' . $i);
            $table = new Table($output);
            $table->setRows($data)->render();
        }

        if ($input->getOption('all')) {
            $io->note('You requested to execute all days until the stable state');
        }

        $io->success('Life is done! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
