<?php

use nstdio\svg\filter\Filter;

class FilterTest extends SVGContextTestCase
{
    public function testName()
    {
        $filter = new Filter($this->svgObj);
        self::assertEquals('filter', $filter->getName());
    }

    public function testShadow()
    {
        $shadowFilter = Filter::shadow($this->svgObj, 10);

        $dom = $this->getDocument();

        $filterNode = $dom->createElement('filter');

        $filterAttrs = ['id' => $shadowFilter->id, 'x' => '-50%', 'y' => '-50%', 'width' => '200%', 'height' => '200%'];
        foreach ($filterAttrs as $key => $value) {
            $filterNode->setAttribute($key, $value);
        }

        $offsetNode = $dom->createElement('feOffset');
        $offsetNode->setAttribute('result', 'offOut');
        $offsetNode->setAttribute('in', 'SourceGraphic');
        $offsetNode->setAttribute('dx', 10);
        $offsetNode->setAttribute('dy', 10);
        $filterNode->appendChild($offsetNode);

        $colorMatrixNode = $dom->createElement('feColorMatrix');
        $colorMatrixNode->setAttribute('result', 'matrixOut');
        $colorMatrixNode->setAttribute('in', 'offOut');
        $colorMatrixNode->setAttribute('type', 'matrix');
        $colorMatrixNode->setAttribute('values', '0.1 0 0 0 0 0 0.1 0 0 0 0 0 0.1 0 0 0 0 0 1 0');
        $filterNode->appendChild($colorMatrixNode);

        $gaussianBlurNode = $dom->createElement('feGaussianBlur');
        $gaussianBlurNode->setAttribute('result', 'blurOut');
        $gaussianBlurNode->setAttribute('stdDeviation', '');
        $filterNode->appendChild($gaussianBlurNode);

        $blendNode = $dom->createElement('feBlend');
        $blendNode->setAttribute('in', 'SourceGraphic');
        $blendNode->setAttribute('in2', 'blurOut');
        $blendNode->setAttribute('mode', 'normal');
        $filterNode->appendChild($blendNode);


        $dom->getElementsByTagName('defs')->item(0)->appendChild($filterNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param $data
     */
    public function testGrayScale($data)
    {
        $method = $data['method'];
        $filter = Filter::$method($this->svgObj, 100);

        $dom = $this->getDocument();
        $filterNode = $dom->createElement('filter');
        $filterNode->setAttribute('id', $filter->id);

        $colorMatrixNode = $dom->createElement('feColorMatrix');
        $colorMatrixNode->setAttribute('type', 'matrix');
        $colorMatrixNode->setAttribute('values', $data['values']);
        $filterNode->appendChild($colorMatrixNode);

        $dom->getElementsByTagName('defs')->item(0)->appendChild($filterNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    public function dataProvider()
    {
        return [
            'grayScale' => [['values' => '0.2126 0.7152 0.0722 0 0
                       0.2126 0.7152 0.0722 0 0
                       0.2126 0.7152 0.0722 0 0
                       0 0 0 1 0', 'method' => 'grayScale']],
            'sepia' => [['values' => '0.393 0.769 0.189 0 0
                       0.349 0.686 0.168 0 0
                       0.272 0.534 0.131 0 0
                       0 0 0 1 0', 'method' => 'sepia']]
        ];
    }

    public function testInvert()
    {
        $filter = Filter::invert($this->svgObj);

        $dom = $this->getDocument();
        $filterNode = $dom->createElement('filter');
        $filterAttrs = ['id' => $filter->id, 'filterUnits' => 'objectBoundingBox', 'x' => '0%', 'y' => '0%', 'width' => '100%', 'height' => '100%'];
        foreach ($filterAttrs as $key => $value) {
            $filterNode->setAttribute($key, $value);
        }

        $componentTransferNode = $dom->createElement('feComponentTransfer');
        $componentTransferNode->setAttribute('id', $filter->getFirstChild()->id);
        $funcRNode = $dom->createElement('feFuncR');
        $funcGNode = $dom->createElement('feFuncG');
        $funcBNode = $dom->createElement('feFuncB');

        $attrs = ['type' => 'table', 'tableValues' => '1 0'];
        foreach ($attrs as $key => $value) {
            $funcRNode->setAttribute($key, $value);
            $funcGNode->setAttribute($key, $value);
            $funcBNode->setAttribute($key, $value);
        }
        $componentTransferNode->appendChild($funcRNode);
        $componentTransferNode->appendChild($funcGNode);
        $componentTransferNode->appendChild($funcBNode);

        $filterNode->appendChild($componentTransferNode);

        $dom->getElementsByTagName('defs')->item(0)->appendChild($filterNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }
}
