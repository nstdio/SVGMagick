<?php

use nstdio\svg\shape\Ellipse;

class EllipseTest extends SVGContextTestCase
{
    /**
     * @var Ellipse
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

        $this->ellipseObj = new Ellipse($this->svgObj, 10, 20, 50, 30);
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
}
