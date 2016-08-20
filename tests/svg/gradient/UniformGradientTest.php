<?php

use nstdio\svg\gradient\Direction;
use nstdio\svg\gradient\UniformGradient;

class UniformGradientTest extends SVGContextTestCase
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
     * @dataProvider linearGradientProvider
     *
     * @param $data
     */
    public function testLinearGradient($data)
    {
        $method = $data['method'];
        $gradient = UniformGradient::$method($this->svgObj, self::$colors);
        $document = $this->linearGradient($data['type'], $gradient->id, $data['direction']);

        self::assertEquals($document->saveHTML(), $this->svgObj->draw());

    }

    private function linearGradient($gradient, $id, $direction)
    {
        $document = $this->getDocument();
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

        $document->documentElement->appendChild($linearGradient);


        return $document;
    }

    public function linearGradientProvider()
    {
        return [
            'verticalFromTop' => [['type' => 'linear', 'method' => 'verticalFromTop', 'direction' => 'topBottom']],
            'verticalFromBottom' => [['type' => 'linear', 'method' => 'verticalFromBottom', 'direction' => 'bottomTop']],
            'diagonalFromTopLeft' => [['type' => 'linear', 'method' => 'diagonalFromTopLeft', 'direction' => 'topLeftBottomRight']],
            'diagonalFromBottomRight' => [['type' => 'linear', 'method' => 'diagonalFromBottomRight', 'direction' => 'bottomRightTopLeft']],
            'diagonalFromBottomLeft' => [['type' => 'linear', 'method' => 'diagonalFromBottomLeft', 'direction' => 'bottomLeftTopRight']],
            'diagonalFromTopRight' => [['type' => 'linear', 'method' => 'diagonalFromTopRight', 'direction' => 'topRightBottomLeft']],
            'horizontalFromLeft' => [['type' => 'linear', 'method' => 'horizontalFromLeft', 'direction' => 'leftRight']],
            'horizontalFromRight' => [['type' => 'linear', 'method' => 'horizontalFromRight', 'direction' => 'rightLeft']],
            'radialTopLeft' => [['type' => 'radial', 'method' => 'radialTopLeft', 'direction' => 'radialTopLeft']],
            'radialTopRight' => [['type' => 'radial', 'method' => 'radialTopRight', 'direction' => 'radialTopRight']],
            'radialBottomLeft' => [['type' => 'radial', 'method' => 'radialBottomLeft', 'direction' => 'radialBottomLeft']],
            'radialBottomRight' => [['type' => 'radial', 'method' => 'radialBottomRight', 'direction' => 'radialBottomRight']],
            'radialBottomCenter' => [['type' => 'radial', 'method' => 'radialBottomCenter', 'direction' => 'radialBottomCenter']],
            'radialRightCenter' => [['type' => 'radial', 'method' => 'radialRightCenter', 'direction' => 'radialRightCenter']],
            'radialTopCenter' => [['type' => 'radial', 'method' => 'radialTopCenter', 'direction' => 'radialTopCenter']],
            'radialLeftCenter' => [['type' => 'radial', 'method' => 'radialLeftCenter', 'direction' => 'radialLeftCenter']],
        ];
    }
}
