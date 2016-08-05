<?php
use nstdio\svg\shape\Polyline;

class PolylineTest extends DOMContextTest
{
    /**
     * @var Polyline
     */
    private $polylineObj;

    public function setUp()
    {
        parent::setUp();
        $this->polylineObj = new Polyline($this->svgObj);
    }

    public function testPolyline()
    {
        $this->polylineObj->addPoint(10, 20);

        self::assertEquals('10,20', $this->polylineObj->points);
    }
}
