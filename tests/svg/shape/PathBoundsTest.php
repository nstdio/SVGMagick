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

        self::assertEquals($expected, $pathBounds->getBox());
    }

    public function dataProvider()
    {
        return [
            'Relative vertical and horizontal lines' => [
                [
                    'expected' => ['x' => 10, 'y' => 10, 'width' => 180, 'height' => 90,],
                    ['M' => [10, 10]], ['h' => [90]], ['v' => [90]], ['h' => [90]],
                ]
            ],
            'Absolute vertical and horizontal lines' => [
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
            'Bezier Absolute' => [
                [
                    'expected' => ['x' => 38.76953125, 'y' => 5, 'height' => 245, 'width' => 279.23046875],
                    ['M' => [318, 5]], ['Q' => [10, 20, 50, 250]], ['C' => [25, 60, 45, 20, 78, 90]],
                ]
            ]
        ];
    }
}
