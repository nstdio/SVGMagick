<?php

use nstdio\svg\gradient\Direction;
use nstdio\svg\shape\Rect;

class ShapeUniformGradientTest extends SVGContextTestCase
{
    /**
     * @var array
     */
    private static $colors;

    /**
     * @var array
     */
    private static $offsets;

    public static function setUpBeforeClass()
    {
        self::$colors = ['black', 'red', 'green', 'blue', 'white'];
        self::$offsets = [0, 0.25, 0.5, 0.75, 1];
    }

    /**
     * @param array $data
     * @dataProvider gradientProvider
     */
    public function testGradient($data)
    {
        $gradientId = 'test';
        $shape = new Rect($this->svgObj, 0, 0, 0, 0);
        $shape->{$data['method']}(self::$colors, $gradientId);

        $doc = $this->shapeWithGradient($data['type'], $gradientId, $data['direction']);
        self::assertEquals($doc->saveHTML(), $this->svgObj->draw());
    }

    private function shapeWithGradient($gradient, $id, $direction)
    {
        $document = $this->getDocument();

        $rect = $document->createElement('rect');
        $rect->setAttribute('height', 0);
        $rect->setAttribute('width', 0);
        $rect->setAttribute('x', 0);
        $rect->setAttribute('y', 0);
        $rect->setAttribute('fill', "url(#$id)");

        $linearGradient = $document->createElement($gradient . 'Gradient');
        $linearGradient->setAttribute('id', $id);
        foreach (Direction::get($direction) as $key => $value) {
            $linearGradient->setAttribute($key, $value);
        }

        foreach (self::$colors as $key => $value) {
            $stop = $document->createElement('stop');
            $stop->setAttribute('offset', self::$offsets[$key]);
            $stop->setAttribute('stop-color', $value);
            $linearGradient->appendChild($stop);
        }
        $document->documentElement->appendChild($rect);

        $document->documentElement->getElementsByTagName('defs')->item(0)->appendChild($linearGradient);


        return $document;
    }

    public function gradientProvider()
    {
        return [
            'linearGradientFromTop' => [['type' => 'linear', 'method' => 'linearGradientFromTop', 'direction' => 'topBottom']],
            'linearGradientFromBottom' => [['type' => 'linear', 'method' => 'linearGradientFromBottom', 'direction' => 'bottomTop']],
            'linearGradientFromLeft' => [['type' => 'linear', 'method' => 'linearGradientFromLeft', 'direction' => 'leftRight']],
            'linearGradientFromRight' => [['type' => 'linear', 'method' => 'linearGradientFromRight', 'direction' => 'rightLeft']],
            'linearGradientFromTopLeft' => [['type' => 'linear', 'method' => 'linearGradientFromTopLeft', 'direction' => 'topLeftBottomRight']],
            'linearGradientFromTopRight' => [['type' => 'linear', 'method' => 'linearGradientFromTopRight', 'direction' => 'topRightBottomLeft']],
            'linearGradientFromBottomLeft' => [['type' => 'linear', 'method' => 'linearGradientFromBottomLeft', 'direction' => 'bottomLeftTopRight']],
            'linearGradientFromBottomRight' => [['type' => 'linear', 'method' => 'linearGradientFromBottomRight', 'direction' => 'bottomRightTopLeft']],
            'radialGradientFromTopLeft' => [['type' => 'radial', 'method' => 'radialGradientFromTopLeft', 'direction' => 'radialTopLeft']],
            'radialGradientFromTopRight' => [['type' => 'radial', 'method' => 'radialGradientFromTopRight', 'direction' => 'radialTopRight']],
            'radialGradientFromBottomLeft' => [['type' => 'radial', 'method' => 'radialGradientFromBottomLeft', 'direction' => 'radialBottomLeft']],
            'radialGradientFromBottomRight' => [['type' => 'radial', 'method' => 'radialGradientFromBottomRight', 'direction' => 'radialBottomRight']],
            'radialGradientFromTopCenter' => [['type' => 'radial', 'method' => 'radialGradientFromTopCenter', 'direction' => 'radialTopCenter']],
            'radialGradientFromLeftCenter' => [['type' => 'radial', 'method' => 'radialGradientFromLeftCenter', 'direction' => 'radialLeftCenter']],
            'radialGradientFromBottomCenter' => [['type' => 'radial', 'method' => 'radialGradientFromBottomCenter', 'direction' => 'radialBottomCenter']],
            'radialGradientFromRightCenter' => [['type' => 'radial', 'method' => 'radialGradientFromRightCenter', 'direction' => 'radialRightCenter']],
        ];
    }
}
