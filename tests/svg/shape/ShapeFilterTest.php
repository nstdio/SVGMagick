<?php

use nstdio\svg\animation\Animate;
use nstdio\svg\container\SVG;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\gradient\LinearGradient;
use nstdio\svg\gradient\Stop;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Rect;

class ShapeFilterTest extends SVGContextTestCase
{
    /**
     * @var Circle
     */
    private $circle;

    /**
     * @var string
     */
    private $idAttr;

    private $cx;
    private $cy;
    private $r;

    public function setUp()
    {
        parent::setUp();

        $this->cx = 150;
        $this->cy = 110;
        $this->r = 80;
        $this->idAttr = 'lin';
        $this->circle = new Circle($this->svgObj, $this->cx, $this->cy, $this->r);
    }

    public function testGradient()
    {
        $linearGradient = new LinearGradient($this->svgObj, $this->idAttr);
        $stop1 = new Stop($linearGradient, ['offset' => '1%', 'stop-color' => 'black']);
        $stop2 = new Stop($linearGradient, ['offset' => '95%', 'stop-color' => 'white']);

        $linearGradient->appendStop($stop1, $stop2);

        $this->svgObj->append($this->circle);
        $this->svgObj->append($linearGradient);

        $this->circle->applyGradient($linearGradient);

        self::assertEquals("url(#$this->idAttr)", $this->circle->fill);
    }

    public function testApplyFilter()
    {
        $filter = new Filter($this->svgObj, $this->idAttr);
        $blur = new GaussianBlur($filter);
        $blur->stdDeviation = 5;

        $filter->append($blur);
        $this->circle->applyFilter($filter);

        self::assertEquals("url(#$this->idAttr)", $this->circle->filter);
    }

    public function testApplyGaussian()
    {
        $this->circle->filterGaussianBlur(5);

        $this->svgObj->append($this->circle);

        $dom = $this->getDocument();
        $circleNode = $dom->createElement('circle');
        $circleNode->setAttribute('cx', $this->cx);
        $circleNode->setAttribute('cy', $this->cy);
        $circleNode->setAttribute('r', $this->r);

        $filterNode = $dom->createElement('filter');

        $filterId = $this->svgObj->getChild('filter');

        self::assertNotEmpty($filterId);
        self::assertArrayHasKey(0, $filterId);

        $filterId = $filterId[0]->id;
        $filterNode->setAttribute('id', $filterId);

        $blur = $dom->createElement('feGaussianBlur');
        $blur->setAttribute('stdDeviation', 5);
        $filterNode->appendChild($blur);

        $circleNode->setAttribute('filter', "url(#$filterId)");
        $dom->documentElement->appendChild($filterNode);
        $dom->documentElement->appendChild($circleNode);

        self::assertEqualXML($dom);
    }

    public function testShapeAnimate()
    {
        $attributeName = 'r';
        $from = 0;
        $to = 80;
        $dur = '2s';
        $attributeType = 'XML';
        $repCount = 'indefinite';
        $animate = new Animate($this->svgObj, $attributeName, $from, $to, $dur, $attributeType, $repCount);
        $this->circle->animate($animate);
        $this->svgObj->append($this->circle);

        $dom = $this->getDocument();
        $circleNode = $dom->createElement('circle');
        $circleNode->setAttribute('cx', $this->cx);
        $circleNode->setAttribute('cy', $this->cy);
        $circleNode->setAttribute('r', $this->r);

        $animateNode = $dom->createElement('animate');
        $animateNode->setAttribute('attributeType', $attributeType);
        $animateNode->setAttribute('attributeName', $attributeName);
        $animateNode->setAttribute('from', $from);
        $animateNode->setAttribute('to', $to);
        $animateNode->setAttribute('dur', $dur);
        $animateNode->setAttribute('repeatCount', $repCount);

        $circleNode->appendChild($animateNode);

        $dom->documentElement->appendChild($circleNode);

        self::assertEqualXML($dom);
    }

    public function testMultiplyFilters()
    {
        $svg = new SVG();

        $diffId = 'diffuse';
        $gaussId = 'gauss';
        $rectId = 'greenRect';

        $rect = new Rect($svg, 0, 0);
        $rect->apply(['fill' => 'green', 'id' => $rectId]);
        $rect->diffusePointLight([], [], $diffId);
        $rect->filterGaussianBlur(1, null, $gaussId);
        $rect->setBorderRadius(5);

        self::assertAttributeCount(2, 'child', $svg);
        self::assertCount(1, $svg->getChild('filter'));
        self::assertCount(1, $svg->getChild('rect'));
        self::assertFalse($rect->hasChild());

        self::assertEquals($svg->getName(), $rect->getRoot()->getName());

        self::assertArrayHasKey(0, $svg->getChild('filter'));
        self::assertArrayNotHasKey(1, $svg->getChild('filter'));

        self::assertEquals($diffId, $svg->getChild('filter')[0]->id);

        self::assertNotNull($svg->getChildById($diffId));
        self::assertEquals($diffId, $svg->getChildById($diffId)->id);

        self::assertNotNull($svg->getChildById($rectId));
        self::assertEquals($rectId, $svg->getChildById($rectId)->id);

        self::assertNull($svg->getChildById($gaussId));
        self::assertNull($svg->getChildById($gaussId)->id);
    }

}
