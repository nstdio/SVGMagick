<?php
use nstdio\svg\shape\Line;

class LineTest extends SVGContextTestCase
{
    /**
     * @var Line
     */
    private $lineObj;

    public function setUp()
    {
        parent::setUp();
        $this->lineObj = new Line($this->svgObj);
    }

    public function testDefaultParams()
    {
        self::assertEquals($this->lineObj->x1, 0);
        self::assertEquals($this->lineObj->y1, 0);
        self::assertEquals($this->lineObj->x2, 0);
        self::assertEquals($this->lineObj->y2, 0);
    }

    /**
     * @depends testDefaultParams
     */
    public function testPassedParams()
    {
        $this->lineObj = new Line($this->svgObj, 0, 5, 20, 50);

        self::assertEquals($this->lineObj->x1, 0);
        self::assertEquals($this->lineObj->y1, 5);
        self::assertEquals($this->lineObj->x2, 20);
        self::assertEquals($this->lineObj->y2, 50);
    }

    public function testConvertToPath()
    {
        $this->lineObj = new Line($this->svgObj, 0, 5, 20, 50);

        $path = $this->lineObj->toPath();

        self::assertEquals("M 0, 5 L 20, 50", $path->d);

        $path = $this->lineObj->toPath(true);

        self::assertEquals("M 0, 5 L 20, 50 Z", $path->d);
    }

    public function testConvertToPolygon()
    {
        $this->lineObj = new Line($this->svgObj, 0, 5, 20, 50);

        $polygon = $this->lineObj->toPolygon();

        self::assertEquals("0,5 20,50", $polygon->points);
    }
}
