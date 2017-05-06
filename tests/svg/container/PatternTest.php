<?php

use nstdio\svg\container\Pattern;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Ellipse;

class PatternTest extends SVGContextTestCase
{
    /**
     * @var Pattern
     */
    private $pattern;

    public function setUp()
    {
        parent::setUp();
        $this->pattern = new Pattern($this->svgObj);
    }

    public function testAutoAppendToDefs()
    {
        self::assertEquals('defs', $this->pattern->getRoot()->getName());
    }

    public function testId()
    {
        $patternId = 'ptrn';
        $pattern = new Pattern($this->svgObj);
        $pattern->id = $patternId;

        self::assertEquals($patternId, $pattern->id);
        self::assertNotNull($this->pattern->id);
    }

    public function testTransformAttribute()
    {
        $this->pattern->setTransformAttribute("rotate(45)");

        self::assertEquals("rotate(45)", $this->pattern->getTransformAttribute());
    }

    public function testWithShape()
    {
        $this->svgObj->getFirstChild()->removeChild($this->pattern);

        $patternShape = new Circle($this->svgObj, 8, 8, 8);
        $shapePattern = Pattern::withShape($this->svgObj, $patternShape);
        $circle2 = new Circle($this->svgObj, 52, 160, 50);
        $circle2->fillUrl = $shapePattern->id;

        $dom = $this->getDocument();
        $patternNode = $dom->createElement('pattern');
        $patternNode->setAttribute('id', $shapePattern->id);
        $patternNode->setAttribute('x', 0);
        $patternNode->setAttribute('y', 0);
        $patternNode->setAttribute('height', 16);
        $patternNode->setAttribute('width', 16);
        $patternNode->setAttribute('patternUnits', 'userSpaceOnUse');

        $circleNode = $dom->createElement('circle');
        $circleNode->setAttribute('cx', 8);
        $circleNode->setAttribute('cy', 8);
        $circleNode->setAttribute('r', 8);

        $patternNode->appendChild($circleNode);

        $circleNode2 = $dom->createElement('circle');
        $circleNode2->setAttribute('cx', 52);
        $circleNode2->setAttribute('cy', 160);
        $circleNode2->setAttribute('r', 50);
        $circleNode2->setAttribute('fill', "url(#" . $shapePattern->id . ")");
        $dom->documentElement->appendChild($circleNode2);

        $dom->getElementsByTagName('defs')->item(0)->appendChild($patternNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    public function testHatch()
    {
        $this->svgObj->getFirstChild()->removeChild($this->pattern);

        $vertical = Pattern::verticalHatch($this->svgObj, ['width' => 2], ['stroke' => 'darkred']);
        $ellipse = new Ellipse($this->svgObj, 420, 50, 70, 30);
        $ellipse->fillUrl = $vertical->id;

        $dom = $this->getDocument();
        $patternNode = $dom->createElement('pattern');
        $patternNode->setAttribute('id', $vertical->id);
        $patternNode->setAttribute('x', 0);
        $patternNode->setAttribute('y', 0);
        $patternNode->setAttribute('height', 4);
        $patternNode->setAttribute('width', 2);
        $patternNode->setAttribute('patternUnits', 'userSpaceOnUse');

        $lineNode = $dom->createElement('line');
        $lineNode->setAttribute('x1', 0);
        $lineNode->setAttribute('y1', 0);
        $lineNode->setAttribute('x2', 0);
        $lineNode->setAttribute('y2', 4);
        $lineNode->setAttribute('stroke', 'darkred');
        $lineNode->setAttribute('stroke-width', 1);
        $lineNode->setAttribute('fill', 'none');

        $patternNode->appendChild($lineNode);

        $ellipseNode = $dom->createElement('ellipse');
        $ellipseNode->setAttribute('cx', $ellipse->cx);
        $ellipseNode->setAttribute('cy', $ellipse->cy);
        $ellipseNode->setAttribute('rx', $ellipse->rx);
        $ellipseNode->setAttribute('ry', $ellipse->ry);
        $ellipseNode->setAttribute('fill', "url(#" . $vertical->id . ")");

        $dom->documentElement->appendChild($ellipseNode);
        $dom->getElementsByTagName('defs')->item(0)->appendChild($patternNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    public function testDiagonalHatch()
    {
        $this->svgObj->getFirstChild()->removeChild($this->pattern);

        $linesConfig = ['stroke' => 'orangered', 'stroke-width' => 0.5, 'fill' => 'none', 'stroke-dasharray' => '1 1'];
        $diagonal = Pattern::diagonalHatch($this->svgObj, ['width' => 10], $linesConfig);
        $circle = new Circle($this->svgObj, 295, 52, 50);
        $circle->fillUrl = $diagonal->id;

        $dom = $this->getDocument();
        $patternNode = $dom->createElement('pattern');
        $patternNode->setAttribute('id', $diagonal->id);
        $patternNode->setAttribute('x', 0);
        $patternNode->setAttribute('y', 0);
        $patternNode->setAttribute('height', 4);
        $patternNode->setAttribute('width', 10);
        $patternNode->setAttribute('patternUnits', 'userSpaceOnUse');
        $patternNode->setAttribute('patternTransform', 'rotate(45)');

        $lineNode = $dom->createElement('line');

        $lineNode->setAttribute('x1', 0);
        $lineNode->setAttribute('y1', 0);
        $lineNode->setAttribute('x2', 0);
        $lineNode->setAttribute('y2', 4);

        foreach ($linesConfig as $key => $value) {
            $lineNode->setAttribute($key, $value);
        }

        $patternNode->appendChild($lineNode);

        $ellipseNode = $dom->createElement('circle');
        $ellipseNode->setAttribute('cx', $circle->cx);
        $ellipseNode->setAttribute('cy', $circle->cy);
        $ellipseNode->setAttribute('r', $circle->r);
        $ellipseNode->setAttribute('fill', "url(#" . $diagonal->id . ")");

        $dom->documentElement->appendChild($ellipseNode);
        $dom->getElementsByTagName('defs')->item(0)->appendChild($patternNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    public function testHorizontalHatch()
    {
        $this->svgObj->getFirstChild()->removeChild($this->pattern);
        $lineConfig = ['x1' => 0, 'y1' => 0, 'x2' => 0, 'y2' => 20, 'stroke' => 'black', 'stroke-width' => 1, 'fill' => 'none'];
        $horizontal = Pattern::horizontalHatch($this->svgObj, ['width' => 20, 'height' => 20]);

        $dom = $this->getDocument();
        $patternNode = $dom->createElement('pattern');
        $patternNode->setAttribute('id', $horizontal->id);
        $patternNode->setAttribute('x', 0);
        $patternNode->setAttribute('y', 0);
        $patternNode->setAttribute('height', 20);
        $patternNode->setAttribute('width', 20);
        $patternNode->setAttribute('patternUnits', 'userSpaceOnUse');
        $patternNode->setAttribute('patternTransform', 'rotate(90)');

        $lineNode = $dom->createElement('line');
        foreach ($lineConfig as $key => $value) {
            $lineNode->setAttribute($key, $value);
        }
        $patternNode->appendChild($lineNode);
        $dom->getElementsByTagName('defs')->item(0)->appendChild($patternNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    /**
     * @dataProvider crossProvider
     *
     * @param $method
     */
    public function testCrossHatch($method)
    {
        $this->svgObj->getFirstChild()->removeChild($this->pattern);

        /** @var Pattern $crossHatch */
        $crossHatch = Pattern::$method($this->svgObj, ['width' => 10]);
        $lineConfig = ['x1' => 0, 'y1' => 5, 'x2' => 10, 'y2' => 5, 'stroke' => 'black', 'stroke-width' => 1, 'fill' => 'none'];
        $line2Config = ['x1' => 5, 'y1' => 0, 'x2' => 5, 'y2' => 10, 'stroke' => 'black', 'stroke-width' => 1, 'fill' => 'none', 'id' => $crossHatch->getChildAtIndex(1)->id];

        $dom = $this->getDocument();
        $patternNode = $dom->createElement('pattern');
        $patternNode->setAttribute('id', $crossHatch->id);
        $patternNode->setAttribute('x', 0);
        $patternNode->setAttribute('y', 0);
        $patternNode->setAttribute('height', 10);
        $patternNode->setAttribute('width', 10);
        $patternNode->setAttribute('patternUnits', 'userSpaceOnUse');
        $patternNode->setAttribute('patternTransform', 'rotate(45)');

        if ($method === 'straightCrossHatch') {
            $patternNode->setAttribute('patternTransform', 'rotate(90)');
        }
        $lineNode = $dom->createElement('line');
        $line2Node = $dom->createElement('line');

        foreach ($lineConfig as $key => $value) {
            $lineNode->setAttribute($key, $value);
        }
        foreach ($line2Config as $key => $value) {
            $line2Node->setAttribute($key, $value);
        }

        $patternNode->appendChild($lineNode);
        $patternNode->appendChild($line2Node);

        $dom->getElementsByTagName('defs')->item(0)->appendChild($patternNode);

        self::assertEquals($dom->saveHTML(), $this->svgObj->draw());
    }

    public function crossProvider()
    {
        return [
            'cross' => ['crossHatch'],
            'straightCross' => ['straightCrossHatch'],
        ];
    }
}
