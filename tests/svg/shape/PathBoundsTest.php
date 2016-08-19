<?php

use nstdio\svg\shape\PathBounds;

class PathBoundsTest extends PHPUnit_Framework_TestCase
{
    public function testPlainData()
    {
        $pathBounds = new PathBounds();
        $pathBounds->addData('M', [50, 15]);

        self::assertEquals([
            'x' => null,
            'y' => null,
            'width' => 0,
            'height' => 0,
        ], $pathBounds->getBox());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param $data
     */
    public function testGetBox($data)
    {
        $pathBounds = new PathBounds();
        $expected = $data['expected'];
        unset($data['expected']);

        foreach ($data as $value) {
            $key = key($value);
            $value = $value[$key];
            $pathBounds->addData($key, $value);
        }

        self::assertEquals($expected, $pathBounds->getBox(), '', 0.001);
    }

    public function dataProvider()
    {
        return [
            'C' => [
                [
                    'expected' => ['x' => 142.726, 'y' => 125, 'width' => 141.768, 'height' => 75],
                    ['M' => [200, 200]], ['C' => [0, 100, 400, 100, 250, 200]],
                ]
            ],
            'c' => [
                [
                    'expected' => ['x' => 100, 'y' => 100, 'height' => 54.052, 'width' => 150],
                    ['M' => [100, 100]], ['c' => [20, 10, 50, 70, 150, 50]],
                ]
            ],
            'v and h' => [
                [
                    'expected' => ['x' => 10, 'y' => 10, 'width' => 180, 'height' => 90,],
                    ['M' => [10, 10]], ['h' => [90]], ['v' => [90]], ['h' => [90]],
                ]
            ],
            'V and H' => [
                [
                    'expected' => ['x' => 10, 'y' => 5, 'width' => 260, 'height' => 175,],
                    ['M' => [10, 10]], ['H' => [90]], ['V' => [180]], ['H' => [270]], ['V' => [5]],
                ]
            ],
            'Line' => [
                [
                    'expected' => ['x' => 20, 'y' => 5, 'width' => 298, 'height' => 53,],
                    ['M' => [318, 5]], ['L' => [20, 48]], ['l' => [10, 10]],
                ]
            ],
            'Q and C' => [
                [
                    'expected' => ['x' => 38.769, 'y' => 5, 'height' => 245, 'width' => 279.230],
                    ['M' => [318, 5]], ['Q' => [10, 20, 50, 250]], ['C' => [25, 60, 45, 20, 78, 90]],
                ]
            ],
            'Q and T' => [
                [
                    'expected' => ['x' => 30, 'y' => 26, 'height' => 104.625, 'width' => 270],
                    ['M' => [30, 100]], ['Q' => [80, 30, 100, 100]], ['T' => [200, 80]], ['T' => [300, 50]]
                ]
            ],
            'T with non curve start' => [
                [
                    'expected' => ['x' => 30, 'y' => 50, 'height' => 112.5, 'width' => 286.3636],
                    ['M' => [30, 100]], ['H' => [40]], ['T' => [200, 150]], ['T' => [300, 50]]
                ]
            ],
            'q and t' => [
                [
                    'expected' => ['x' => 100, 'y' => 100, 'height' => 150, 'width' => 366.666],
                    ['M' => [100, 100]], ['q' => [200, 0, 300, 100]], ['t' => [50, 0]],
                ]
            ],
            't with non curve start' => [
                [
                    'expected' => ['x' => 50, 'y' => 100, 'height' => 70, 'width' => 50],
                    ['M' => [100, 100]], ['H' => [50]], ['v' => [20]], ['t' => [50, 50]]
                ]
            ],
            'C and S' => [
                [
                    'expected' => ['x' => 54.219, 'y' => 46.579, 'height' => 153.42, 'width' => 128.174],
                    ['M' => [100, 100]], ['C' => [20, 10, 50, 70, 150, 50]], ['S' => [80, 100, 150, 200]],
                ]
            ],
            'C and s' => [
                [
                    'expected' => ['x' => 54.219, 'y' => 48.625, 'height' => 201.374, 'width' => 245.780],
                    ['M' => [100, 100]], ['C' => [20, 10, 50, 70, 150, 50]], ['s' => [80, 100, 150, 200]],
                ]
            ],
            'Q and S' => [
                [
                    'expected' => ['x' => 41.818, 'y' => 46, 'height' => 154, 'width' => 108.181],
                    ['M' => [100, 100]], ['Q' => [20, 10, 50, 70]], ['S' => [80, 100, 150, 200]],
                ]
            ],
        ];
    }
}
