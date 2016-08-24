<?php

use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Ellipse;

class EllipseTest extends SVGContextTestCase
{
    /**
     * @var EllipseCenterTest
     */
    private $ellipseObj;

    private $styleArray;

    public function setUp()
    {
        parent::setUp();
        $this->styleArray = [
            'text-align'          => 'center',
            'color'               => '#4d4e53',
            'background-repeat'   => 'repeat',
            'background-position' => '0 0,0 0,0 0',
            'background-color'    => '#eaeff2',
            'font-family'         => "'Open Sans',Arial,sans-serif",
            'line-height'         => '1.5',
        ];

        $this->ellipseObj = new EllipseCenterTest($this->svgObj, 10, 20, 50, 30);
    }

    public function testEllipse()
    {
        self::assertEquals(10, $this->ellipseObj->cx);
        self::assertEquals(20, $this->ellipseObj->cy);
        self::assertEquals(50, $this->ellipseObj->rx);
        self::assertEquals(30, $this->ellipseObj->ry);
    }

    public function testStyleString()
    {
        $this->ellipseObj->setStyle("color:red;");

        self::assertEquals('color:red;', $this->ellipseObj->style);
    }

    public function testStyleArray()
    {
        $this->ellipseObj->setStyle($this->styleArray);

        self::assertEquals("text-align:center;color:#4d4e53;background-repeat:repeat;background-position:0 0,0 0,0 0;background-color:#eaeff2;font-family:'Open Sans',Arial,sans-serif;line-height:1.5;", $this->ellipseObj->style);
    }

    public function testBoundingBox()
    {
        $bbox = [
            'width'  => 2 * $this->ellipseObj->rx,
            'height' => 2 * $this->ellipseObj->ry,
            'x'      => abs($this->ellipseObj->cx - $this->ellipseObj->rx),
            'y'      => abs($this->ellipseObj->cy - $this->ellipseObj->ry),
        ];

        self::assertEquals($bbox, $this->ellipseObj->getBoundingBox());
        self::assertEquals($this->ellipseObj->cx, $this->ellipseObj->getCenterX());
        self::assertEquals($this->ellipseObj->cy, $this->ellipseObj->getCenterY());
    }

    public function testCircleBoundingBox()
    {
        $cx = $cy = $r = 10;
        $circle = new Circle($this->svgObj, $cx, $cy, $r);

        $bbox = [
            'width' => 2 * $r,
            'height' => 2 * $r,
            'x' => abs($cx - $r),
            'y' => abs($cx - $r),
        ];

        self::assertEquals($bbox, $circle->getBoundingBox());
    }
}

class EllipseCenterTest extends Ellipse
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
