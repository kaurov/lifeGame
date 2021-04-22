<?php

namespace App\Helper;

use App\Model\ValueObject\SocietyInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SocietyHelper
{
    /**
     * SocietyHelper constructor.
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param SocietyInterface $society
     * @param bool $isAnimate
     * @param string $title
     * @return string
     */
    public function display(SocietyInterface $society, bool $isAnimate = true, string $title = ''): self
    {
        if ($isAnimate) {
            $this->clearScreen();
        }
        $this->output->writeln('');
        $this->output->writeln($title);
        $this->output->writeln($this->render($society));
        return $this;
    }

    private function clearScreen(): self
    {
        $this->output->write(sprintf("\033\143"));
        return $this;
    }

    /**
     * @param SocietyInterface $life
     * @return string
     */
    private function render(SocietyInterface $life): string
    {
        $output = '';
        foreach ($life->getGrid() as $widthId => $width) {
            foreach ($width as $heightId => $height) {
                if ($height == 1) {
                    $output .= '* ';
                } else {
                    $output .= '. ';
                }
            }
            $output .= PHP_EOL;
        }

        return $output;
    }
}
