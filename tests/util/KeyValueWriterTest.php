<?php

use nstdio\svg\container\SVG;
use nstdio\svg\util\KeyValueWriter;

class KeyValueWriterTest extends PHPUnit_Framework_TestCase
{

    private $styleArray;

    public function setUp()
    {
        $this->styleArray = [
            'text-align'          => 'center',
            'color'               => '#4d4e53',
            'background-repeat'   => 'repeat',
            'background-position' => '0 0,0 0,0 0',
            'background-color'    => '#eaeff2',
            'font-family'         => "'Open Sans',Arial,sans-serif",
            'line-height'         => '1.5',
        ];
    }

    public function testApply()
    {
        $svg = new SVG();

        $attributes = [
            'version' => '1.1',
            'viewBox' => '0 0 1200 400',
            'class'   => 'cls',
            'id'      => 'svg_element',
        ];
        KeyValueWriter::apply($svg->getRoot(), $attributes);

        foreach ($attributes as $key => $value) {
            self::assertEquals($value, $svg->getElement()->getAttribute($key));
        }
    }

    public function testArray2String()
    {
        $keyValueDeilm = '!';
        $valueEndDelim = '!!';
        $styleArray = [
            'background-color' => 'red',
            'text-align'       => 'center',
        ];
        $result = KeyValueWriter::array2String($styleArray, $keyValueDeilm, $valueEndDelim);

        self::assertEquals('background-color!red!!text-align!center!!', $result);

        $expectEmptyString = KeyValueWriter::array2String(['' => 0], 'd', 'd');

        self::assertEquals('', $expectEmptyString);
    }

    public function testStyleArrayToString()
    {
        $expected = KeyValueWriter::styleArrayToString($this->styleArray);
        $this->assertEqualsStyle($expected);
    }

    public function testStyleArrayToStringRecursive()
    {
        $styleArray = [
            'style' => [
                's' => [
                    't' => [
                        $this->styleArray
                    ],
                ],
            ],
        ];

        $expected = KeyValueWriter::styleArrayToString($styleArray);
        $this->assertEqualsStyle($expected);
    }

    private function assertEqualsStyle($expect)
    {
        self::assertEquals("text-align:center;color:#4d4e53;background-repeat:repeat;background-position:0 0,0 0,0 0;background-color:#eaeff2;font-family:'Open Sans',Arial,sans-serif;line-height:1.5;", $expect);
    }
}
