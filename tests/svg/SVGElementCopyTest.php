<?php

use nstdio\svg\container\G;
use nstdio\svg\shape\Line;
use nstdio\svg\shape\Rect;

class SVGElementCopyTest extends SVGContextTestCase
{
    public function testCopy()
    {
        $line = new Line($this->svgObj, 0, 0, 10, 10);
        $line->id = 'ln';

        $lineCopy = $line->copy(['x1' => 10, 'y1' => 10, 'x2' => 0, 'y2' => 0]);

        self::assertAttributeCount(3, 'child', $this->svgObj);
        self::assertEquals($lineCopy, $this->svgObj->getChildAtIndex(2));
        self::assertSame($lineCopy, $this->svgObj->getChildAtIndex(2));

        self::assertNotEquals($lineCopy->id, $line->id);
        self::assertEquals(10, $lineCopy->x1);
        self::assertEquals(10, $lineCopy->y1);
        self::assertEquals(0, $lineCopy->x2);
        self::assertEquals(0, $lineCopy->y2);
    }

    public function testCopyWithParent()
    {
        $rect = new Rect($this->svgObj, 0, 0);
        $rect->apply(['stroke' => 'green']);

        $g = (new G($this->svgObj))->apply(['stroke' => $rect->stroke]);

        $rectCopy = $rect->copy([], ['stroke'], $g);

        self::assertAttributeCount(1, 'child', $g);
        self::assertNotEquals($rectCopy->stroke, $rect->stroke);
    }
}
