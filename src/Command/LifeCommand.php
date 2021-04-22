<?php

namespace App\Command;

use App\Helper\SocietyHelper;
use App\Model\ValueObject\Society;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class LifeCommand
 * @sample php bin/console app:life
 * @sample php bin/console app:life --days=5
 * @sample php bin/console app:life --all=y
 * @sample php bin/console app:life --days=5 --animate=y
 * @sample php bin/console app:life --animate=y --random=y --all=y
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
            )
            ->addOption(
                'animate',
                null,
                InputOption::VALUE_OPTIONAL,
                'Display animated grid, otherwise you will get the separate output for each day'
            )
            ->addOption(
                'random',
                null,
                InputOption::VALUE_OPTIONAL,
                'Display animated grid, otherwise you will get the separate output for each day'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $society = new Society();
        $societyHelper = new SocietyHelper($output);

        if ($input->getOption('random')) {
            $society->setRandomGrid();
        } else {
            $data = [
                [0, 1, 0],
                [0, 0, 1],
                [1, 1, 1],
            ];
            $society->setGrid($data);
        }

        $isAnimate = $input->getOption('animate');
        $isAll = $input->getOption('all');
        if ($isAll) {
            $io->note('You requested to display the eternal society');
        } else {
            $days = $input->getOption('days');
            $days = (int)$days;
            $days = $days >= 1 ? $days : 1;
            $io->note('You requested to execute ' . $days . ' iterations');
        }
        $i = 0;
        while ($isAll || $i <= $days) {
            $societyHelper->display($society, (bool)$isAnimate, 'Day ' . $i);
            $society->live();
            if ($isAnimate) {
                \sleep(1);
            };
            $i++;
        }

        $io->success('Life is done! Pass --help to see your options.');
        return Command::SUCCESS;
    }

}
