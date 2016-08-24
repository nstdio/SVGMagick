<?php

use nstdio\svg\shape\Rect;

class RectTest extends SVGContextTestCase
{
    /**
     * @var Rect
     */
    private $rectObj;

    public function setUp()
    {
        parent::setUp();

        $this->rectObj = new Rect($this->svgObj, 100, 200);
    }

    public function testBorderRadius()
    {
        $this->rectObj->setBorderRadius(2.3);

        self::assertEquals($this->rectObj->rx, 2.3);
        self::assertEquals($this->rectObj->ry, 2.3);
    }

    public function testConvertToPath()
    {
        $this->rectObj->setBorderRadius(5);
        $this->rectObj->toPath();

    }

    public function testConvertToPathNoR()
    {
        $this->rectObj->fill = 'red';
        $this->rectObj->stroke ='green';
        $this->rectObj->strokeWidth = 0.5;
        $this->rectObj->toPath();

    }

    public function testBoundingBox()
    {
        $bbox = [
            'width' => $this->rectObj->width,
            'height' => $this->rectObj->height,
            'x' => $this->rectObj->x,
            'y' => $this->rectObj->y,
        ];

        self::assertEquals($bbox, $this->rectObj->getBoundingBox());
    }
}
