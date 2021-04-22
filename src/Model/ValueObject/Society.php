<?php

namespace App\Model\ValueObject;


/**
 * Class Society ValueObject
 * @copyright Philip Norton https://www.hashbangcode.com/article/conways-game-life-php
 * @copyright Eugene Kaurov
 */
class Society implements SocietyInterface
{

    /**
     * X-axis canvas size, columns
     */
    public const MAX_X = 25;

    /**
     * Y-axis canvas size, rows
     */
    public const MAX_Y = 25;

    /**
     * @var array grid of cells which will live and die
     */
    private array $grid;

    /**
     * @codeCoverageIgnore
     * @return array
     */
    public function getGrid(): array
    {
        return $this->grid;
    }

    /**
     * @codeCoverageIgnore
     * @param array $grid
     * @return Society
     */
    public function setGrid(array $grid): self
    {
        $this->grid = $grid;
        return $this;
    }


    /**
     * @return Society
     */
    public function setRandomGrid(): self
    {
        for ($i = 1; $i <= static::MAX_Y; ++$i) {
            $height = [];
            for ($j = 1; $j <= static::MAX_X; ++$j) {
                $height[$j] = \round(\rand(0, 1));
            }
            $this->grid[$i] = $height;
        }
        return $this;
    }

    /**
     * Make one day of life iteration (die and born)
     * @return Society
     */
    public function live(): self
    {
        $newGrid = [];
        foreach ($this->grid as $rowId => $row) {
            if (!is_array($row)) {
                throw new \RuntimeException('Please provide 2 dimentional array');
            }
            $newGrid[$rowId] = [];
            foreach ($row as $colId => $cell) {
                $count = $this->getNeighborsCount($rowId, $colId);

                if ($cell == 1) {
                    // The cell is alive.
                    if ($count < 2 || $count > 3) {
                        // Any live cell with fewer than two live neighbors dies as if caused by underpopulation.
                        // Any live cell with more than three live neighbors dies, as if by overcrowding.
                        $cell = 0;
                    }
                    // Any live cell with two or three live neighbors lives on to the next generation.
                } elseif ($count == 3) {
                    // Any dead cell with exactly three live neighbors becomes a live cell, as if by reproduction.
                    $cell = 1;
                }

                $newGrid[$rowId][$colId] = $cell;
            }
        }
        $this->setGrid($newGrid);
        unset($newGrid);
        return $this;
    }


    private function getNeighborsCount($x, $y): int
    {
        $coordinatesArray = [
            [-1, -1],
            [-1, 0],
            [-1, 1],
            [0, -1],
            [0, 1],
            [1, -1],
            [1, 0],
            [1, 1]
        ];

        $count = 0;

        foreach ($coordinatesArray as $coordinate) {
            if (
                isset($this->grid[$x + $coordinate[0]][$y + $coordinate[1]])
                && $this->grid[$x + $coordinate[0]][$y + $coordinate[1]] == 1
            ) {
                $count++;
            }
        }
        return $count;
    }

}
