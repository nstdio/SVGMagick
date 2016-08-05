<?php

use nstdio\svg\shape\Path;

class PathTest extends DOMContextTest
{
    /**
     * @var Path
     */
    private $pathObj;

    public function setUp()
    {
        parent::setUp();
        $this->pathObj = new Path($this->svgObj, 100, 100);
    }

    public function testIdentifiersOrder()
    {
        self::expectException('InvalidArgumentException');
        self::expectExceptionMessage('First modifier for path must be: M');

        $this->pathObj->moveTo(20, 20);
    }

    public function testArcTo()
    {
        $this->pathObj
            ->arcTo(25, 25, -30, 0, 1, 50, -25, false)
            ->closePath();

        $d = 'M 100, 100 ';

        $d .= 'a 25, 25 -30 0, 1 50, -25 ';
        $d .= 'Z';

        self::assertEquals($d, $this->pathObj->d);
    }

    public function testPath()
    {
        $this->pathObj->hLineTo(300)
            ->vLineTo(400, false)
            ->lineTo(200, 210)
            ->curveTo(100, 100, 250, 100, 250, 200)
            ->smoothCurveTo(875, 900, 900, 800)
            ->quadraticCurveTo(550, 200, 250, 400)
            ->smoothQuadraticCurveTo(20, 500)
            ->smoothQuadraticCurveTo(10, 250, false)
            ->arcTo(25, 25, -30, 0, 1, 50, -25, false)
            ->closePath();

        $d = 'M 100, 100 ';
        $d .= 'H 300 ';
        $d .= 'v 400 ';
        $d .= 'L 200, 210 ';
        $d .= 'C 100, 100 250, 100 250, 200 ';
        $d .= 'S 875, 900 900, 800 ';
        $d .= 'Q 550, 200 250, 400 ';
        $d .= 'T 20, 500 ';
        $d .= 't 10, 250 ';

        $d .= 'a 25, 25 -30 0, 1 50, -25 ';
        $d .= 'Z';
        self::assertEquals($d, $this->pathObj->d);
    }
}
