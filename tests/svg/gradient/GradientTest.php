<?php

use nstdio\svg\gradient\UniformGradient;

class GradientTest extends SVGContextTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testUniformGradient()
    {
        $actual = UniformGradient::uniformGradient($this->svgObj, ['red', 'green', 'red', 'blue', 'cyan']);
        $this->svgObj->append($actual);

        $dom = $this->getDocument();
        $expect = [
            ['offset' => 0, 'stop-color' => 'red'],
            ['offset' => 0.25, 'stop-color' => 'green'],
            ['offset' => 0.5, 'stop-color' => 'red'],
            ['offset' => 0.75, 'stop-color' => 'blue'],
            ['offset' => 1, 'stop-color' => 'cyan'],
        ];
        $gradient = $dom->createElement('linearGradient');
        $gradient->setAttribute('id', $actual->id);

        foreach ($expect as $item) {
            $stop = $dom->createElement('stop');
            $stop->setAttribute('offset', $item['offset']);
            $stop->setAttribute('stop-color', $item['stop-color']);
            $gradient->appendChild($stop);
        }
        $dom->documentElement->appendChild($gradient);

        self::assertEqualXML($dom);
    }

    public function testUniformGradientOneColor()
    {
        $actual = UniformGradient::uniformGradient($this->svgObj, ['red']);
        $this->svgObj->append($actual);

        $expect = [
            ['offset' => 0, 'stop-color' => 'red'],
            ['offset' => 1, 'stop-color' => 'red'],
        ];
        $dom = $this->getDocument();
        $gradient = $dom->createElement('linearGradient');
        $gradient->setAttribute('id', $actual->id);
        foreach ($expect as $item) {
            $stop = $dom->createElement('stop');
            $stop->setAttribute('offset', $item['offset']);
            $stop->setAttribute('stop-color', $item['stop-color']);
            $gradient->appendChild($stop);
        }
        $dom->documentElement->appendChild($gradient);

        self::assertEqualXML($dom);
    }

    public function testUniformGradientEmptyColor()
    {
        $actual = UniformGradient::uniformGradient($this->svgObj, [], null, 'r');
        $this->svgObj->append($actual);
        $expect = [
            ['offset' => 0, 'stop-color' => 'white'],
            ['offset' => 1, 'stop-color' => 'black'],
        ];
        $dom = $this->getDocument();
        $gradient = $dom->createElement('linearGradient');
        $gradient->setAttribute('id', $actual->id);
        foreach ($expect as $item) {
            $stop = $dom->createElement('stop');
            $stop->setAttribute('offset', $item['offset']);
            $stop->setAttribute('stop-color', $item['stop-color']);
            $gradient->appendChild($stop);
        }
        $dom->documentElement->appendChild($gradient);
        self::assertEqualXML($dom);
    }
}
