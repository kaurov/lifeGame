<?php

namespace App\Model\ValueObject;

use PHPUnit\Framework\TestCase;

class SocietyTest extends TestCase
{

    /**
     * @var Society
     */
    private Society $society;

    public function setUp(): void
    {
        $this->society = new Society();
    }

    public function testSetRandomGrid(): void
    {
        self::assertEquals(0, \sizeof($this->society->setGrid([])->getGrid()));
        self::assertEquals($this->society::MAX_X, \sizeof($this->society->setRandomGrid()->getGrid()));
    }

    /**
     * @dataProvider liveSamples
     * @param array $expected
     * @param array $incoming
     */
    public function testLive(array $expected = [[0]], array $incoming = [[1]]): void
    {
        self::assertEquals($expected, $this->society->setGrid($incoming)->live()->getGrid());
    }

    public function liveSamples(): \Generator
    {
        yield '1 line die' => [
            'expected' => [
                [0],
            ],
            'incoming' => [
                [1],
            ],
        ];

        yield '1 line 2 neighbors 2 dimentions' => [
            'expected' => [
                [0, 1, 0],
            ],
            'incoming' => [
                [1, 1, 1],
            ],
        ];

        yield 'task 1st step' => [
            'expected' => [
                [0, 0, 0],
                [1, 0, 1],
                [0, 1, 1],
            ],
            'incoming' => [
                [0, 1, 0],
                [0, 0, 1],
                [1, 1, 1],
            ],
        ];

        yield 'task 2nd step' => [
            'expected' => [
                [0, 0, 0],
                [0, 1, 1],
                [0, 1, 1],

            ],
            'incoming' => [
                [0, 0, 0],
                [0, 0, 1],
                [0, 1, 1],
            ],
        ];

        yield '3 neighbours born new' => [
            'expected' => [
                [1, 1],
                [1, 1],
            ],
            'incoming' => [
                [0, 1],
                [1, 1],
            ],
        ];

        yield '2 neighbours keep alive' => [
            'expected' => [
                [1, 1],
                [1, 1],
            ],
            'incoming' => [
                [0, 1],
                [1, 1],
            ],
        ];

        yield 'dies: 1 neighbour' => [
            'expected' => [
                [0, 0],
                [0, 0],
            ],
            'incoming' => [
                [0, 1],
                [1, 0],
            ],
        ];

        yield '1 dies alone' => [
            'expected' => [
                [0, 0],
                [0, 0],
            ],
            'incoming' => [
                [0, 1],
                [0, 0],
            ],
        ];
    }

    /**
     * @dataProvider liveExceptionSamples
     * @param array $incoming
     */
    public function testLiveExceptions(array $incoming = [1]): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage = "Please provide 2 dimentional array";
        $this->society->setGrid($incoming)->live()->getGrid();
    }

    public function liveExceptionSamples(): \Generator
    {
        yield '1 dies' => [
            'incoming' => [1]
        ];

        yield '1 line 2 neighbors' =>
        [
            'incoming' => [1, 1, 1],
        ];
    }

}

