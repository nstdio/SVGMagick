<?php

use nstdio\svg\animation\Set;

class SetTest extends SVGContextTestCase
{
    public function testName()
    {
        $set = new Set($this->svgObj);

        self::assertEquals('set', $set->getName());
    }
}
