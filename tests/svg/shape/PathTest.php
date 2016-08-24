<?php

use nstdio\svg\shape\Path;

class PathTest extends SVGContextTestCase
{
    /**
     * @var PathCenterTest
     */
    private $pathObj;

    public function setUp()
    {
        parent::setUp();
        $this->pathObj = new PathCenterTest($this->svgObj, 100, 100);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage First modifier for path must be: M or m
     */
    public function testIdentifiersOrder()
    {
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

    public function testBoundingBox()
    {
        $this->pathObj
            ->hLineTo(90, false)
            ->vLineTo(90, false)
            ->hLineTo(90, false);
        $bbox = [
            'width' => 180,
            'height' => 90,
            'x' => 100,
            'y' => 100,
        ];

        self::assertEquals($bbox, $this->pathObj->getBoundingBox());
        self::assertEquals($bbox['width'] / 2, $this->pathObj->getCenterX());
        self::assertEquals($bbox['height'] / 2, $this->pathObj->getCenterY());
    }
}

class PathCenterTest extends Path
{
    public function getCenterX()
    {
        return parent::getCenterX();
    }

    public function getCenterY()
    {
        return parent::getCenterY();
    }
}
