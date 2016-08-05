<?php

use nstdio\svg\gradient\Direction;

class DirectionTest extends PHPUnit_Framework_TestCase
{
    public function testRadial()
    {
        $direction = Direction::get('FromDummyToSmarty');

        self::assertEmpty($direction);
        self::assertEquals([], $direction);


        $direction = Direction::get('topLeftBottomRight');

        self::assertEquals([
            'x1' => '0%',
            'y1' => '0%',
            'x2' => '100%',
            'y2' => '100%',
        ], $direction);

        $radialDirection = Direction::get('radialTopLeft');

        self::assertEquals([
            'fx' => '0%',
            'fy' => '0%',
            'r' => '100%',
        ], $radialDirection);
    }
}
