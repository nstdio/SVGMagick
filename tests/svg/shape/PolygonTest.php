<?php

use nstdio\svg\shape\Polygon;

class PolygonTest extends SVGContextTestCase
{

    /**
     * @var Polygon
     */
    private $polygonObj;

    /**
     * @var
     */
    private $points;

    public function setUp()
    {
        parent::setUp();
        $this->polygonObj = new Polygon($this->svgObj);
        $this->points = [[850, 75], [958, 137.5], [958, 262.5], [850, 325], [742, 262.6], [742, 137.5]];
    }

    public function testEmptyPolygon()
    {
        self::assertNull($this->polygonObj->points);
    }

    public function testPolygonPoints()
    {
        $this->polygonObj
            ->addPoint(850, 75)
            ->addPoint(958, 137.5)
            ->addPoint(958, 262.5)
            ->addPoint(850, 325)
            ->addPoint(742, 262.6)
            ->addPoint(742, 137.5);

        self::assertEquals($this->pointsAsString(), $this->polygonObj->points);
    }

    private function pointsAsString($forPath = false)
    {
        $ret = '';
        foreach ($this->points as $key => $point) {
            if ($forPath === true && $key === 0) {
                $ret .= "M $point[0], $point[1] ";

            } else {
                if ($forPath === true) {
                    $ret .= "L $point[0], $point[1] ";
                } else {
                    $ret .= "$point[0],$point[1] ";
                }
            }
        }

        return trim($ret);
    }

    /**
     * @depends testPolygonPoints
     */
    public function testPolygonPointsArray()
    {
        $this->polygonObj->addPointArray(array_merge([560], $this->points));

        self::assertEquals($this->pointsAsString(), $this->polygonObj->points);
    }

    public function testConvertToPath()
    {
        $this->polygonObj->addPointArray($this->points);
        $path = $this->polygonObj->toPath();

        self::assertEquals($this->pointsAsString(true), $path->d);
    }

    public function testFailConverting()
    {
        $path = $this->polygonObj->toPath();

        self::assertNull($path);
    }
}
