<?php

use nstdio\svg\shape\PathBounds;

class PathBoundsTest extends PHPUnit_Framework_TestCase
{
    public function testPlainData()
    {
        $pathBounds = new PathBounds();
        $pathBounds->addData('M', [50, 15]);

        self::assertEqualsRect([
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
        self::markTestIncomplete('TODO: Fix PathBounds.');
        $pathBounds = new PathBounds();
        $expected = $data['expected'];
        unset($data['expected']);

        foreach ($data as $value) {
            $key = key($value);
            $value = $value[$key];
            $pathBounds->addData($key, $value);
        }

        self::assertEqualsRect($expected, $pathBounds->getBox());
    }

    public function dataProvider()
    {
        return [
            'Relative vertical and horizontal lines' => [
                [
                    'expected' => ['x' => 10, 'y' => 10, 'width' => 180, 'height' => 90,],
                    ['M' => [10, 10]],
                    ['h' => [90]],
                    ['v' => [90]],
                    ['h' => [90]],
                ]
            ]
        ];
    }

    private static function assertEqualsRect($expected, $actual)
    {
        self::assertArrayHasKey('x', $actual);
        self::assertArrayHasKey('y', $actual);
        self::assertArrayHasKey('width', $actual);
        self::assertArrayHasKey('height', $actual);

        self::assertEquals($expected, $actual);
    }
}
