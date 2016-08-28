<?php

use nstdio\svg\filter\ColorMatrix;

class ColorMatrixTest extends SVGContextTestCase
{
    /**
     * @dataProvider colorMatrixProvider
     *
     * @param $data
     */
    public function testFactoryMethods($data)
    {
        $method = $data['method'];
        $filter = ColorMatrix::$method($this->svgObj, $data['values']);

        $dom = $this->getDocument();

        $filterAttrs = ['id' => $filter->id, 'filterUnits' => 'objectBoundingBox', 'x' => '0%', 'y' => '0%', 'width' => '100%', 'height' => '100%'];
        $filterNode = $dom->createElement('filter');
        foreach ($filterAttrs as $key => $value) {
            $filterNode->setAttribute($key, $value);
        }
        $colorMatrixNode = $dom->createElement('feColorMatrix');
        $colorMatrixNode->setAttribute('type', isset($data['type']) ? $data['type'] : $method);
        $colorMatrixNode->setAttribute('in', 'SourceGraphic');
        if (isset($data['valResult'])) {
            $colorMatrixNode->setAttribute('values', $data['valResult']);
        }
        if ($method === 'luminanceToAlpha' || $method === 'luminanceToAlphaWithComposite') {
            $colorMatrixNode->setAttribute('result', 'a');
        }

        $filterNode->appendChild($colorMatrixNode);

        if ($method === 'luminanceToAlphaWithComposite') {
            $compositeNode = $dom->createElement('feComposite');
            $attrs = ['in' => 'SourceGraphic', 'k1' => 0, 'k2' => 0, 'k3' => 0, 'k4' => 0, 'in2' => 'a', 'operator' => 'in'];

            foreach ($attrs as $key => $value) {
                $compositeNode->setAttribute($key, $value);
            }
            $filterNode->appendChild($compositeNode);
        }

        $dom->getElementsByTagName('defs')->item(0)->appendChild($filterNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    /**
     * @expectedException \nstdio\svg\xml\NotImplementedException
     */
    public function testMatrix()
    {
        ColorMatrix::matrix($this->svgObj, []);
    }

    public function colorMatrixProvider()
    {
        return [
            'saturate'                      => [['method' => 'saturate', 'values' => 1, 'valResult' => 1]],
            'saturate2'                     => [['method' => 'saturate', 'values' => 10, 'valResult' => 0.1]],
            'hueRotate'                     => [['method' => 'hueRotate', 'values' => 45, 'valResult' => 45]],
            'luminanceToAlpha'              => [['method' => 'luminanceToAlpha', 'values' => 'lum']],
            'luminanceToAlphaWithComposite' => [
                ['method' => 'luminanceToAlphaWithComposite', 'type' => 'luminanceToAlpha', 'values' => null],
            ],
        ];
    }
}
