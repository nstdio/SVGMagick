<?php

use nstdio\svg\animation\Animate;
use nstdio\svg\animation\AnimateMotion;
use nstdio\svg\animation\MPath;
use nstdio\svg\animation\Set;
use nstdio\svg\container\A;
use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\container\Marker;
use nstdio\svg\container\Mask;
use nstdio\svg\container\Pattern;
use nstdio\svg\container\SVG;
use nstdio\svg\container\Swtch;
use nstdio\svg\container\Symbol;
use nstdio\svg\desc\Desc;
use nstdio\svg\desc\Metadata;
use nstdio\svg\desc\Title;
use nstdio\svg\ElementInterface;
use nstdio\svg\filter\Blend;
use nstdio\svg\filter\ColorMatrix;
use nstdio\svg\filter\ComponentTransfer;
use nstdio\svg\filter\Composite;
use nstdio\svg\filter\ConvolveMatrix;
use nstdio\svg\filter\DiffuseLighting;
use nstdio\svg\filter\DisplacementMap;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\Flood;
use nstdio\svg\filter\FuncA;
use nstdio\svg\filter\FuncB;
use nstdio\svg\filter\FuncG;
use nstdio\svg\filter\FuncR;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\filter\Image;
use nstdio\svg\filter\Merge;
use nstdio\svg\filter\MergeNode;
use nstdio\svg\filter\Morphology;
use nstdio\svg\filter\Offset;
use nstdio\svg\filter\SpecularLighting;
use nstdio\svg\filter\Tile;
use nstdio\svg\filter\Turbulence;
use nstdio\svg\font\Font;
use nstdio\svg\font\FontFace;
use nstdio\svg\font\FontFaceFormat;
use nstdio\svg\font\FontFaceName;
use nstdio\svg\font\FontFaceSrc;
use nstdio\svg\font\FontFaceUri;
use nstdio\svg\font\HKern;
use nstdio\svg\font\MissingGlyph;
use nstdio\svg\font\VKern;
use nstdio\svg\gradient\LinearGradient;
use nstdio\svg\gradient\RadialGradient;
use nstdio\svg\gradient\Stop;
use nstdio\svg\light\DistantLight;
use nstdio\svg\light\PointLight;
use nstdio\svg\light\SpotLight;
use nstdio\svg\shape\Circle;
use nstdio\svg\shape\Ellipse;
use nstdio\svg\shape\Line;
use nstdio\svg\shape\Path;
use nstdio\svg\shape\Polygon;
use nstdio\svg\shape\Polyline;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\AltGlyph;
use nstdio\svg\text\AltGlyphDef;
use nstdio\svg\text\AltGlyphItem;
use nstdio\svg\text\Glyph;
use nstdio\svg\text\GlyphRef;
use nstdio\svg\text\Text;
use nstdio\svg\text\TextPath;
use nstdio\svg\text\TRef;
use nstdio\svg\text\TSpan;

class SVGTest extends SVGContextTestCase
{
    /**
     * @var Circle
     */
    private $circle;

    private $element;


    public function setUp()
    {
        parent::setUp();
        $this->circle = new Circle($this->svgObj, 200, 200, 200);
        $this->element = $this->svgObj->getElement();
    }

    public function testSVGWithDefaultParams()
    {
        $svg = new SVG();
        $this->width = $svg->getRoot()->getAttribute('width');
        $this->height = $svg->getRoot()->getAttribute('height');

        $this->assertSvgEqualsSvg($svg);
    }

    private function assertSvgEqualsSvg(SVG $svg)
    {
        self::assertEquals("<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"{$this->width}\" height=\"{$this->height}\" version=\"1.1\" viewBox=\"0 0 {$this->width} {$this->height}\"><defs></defs></svg>", rtrim($svg->draw()));
    }

    public function testSVGWithParams()
    {
        $svg = new SVG($this->width, $this->height);

        $this->assertSvgEqualsSvg($svg);
    }

    public function testSvgWithOneChild()
    {

        $this->circle->fill = 'red';

        $this->svgObj->append($this->circle);

        $document = $this->getDocument();

        $circleNode = $document->createElement('circle');
        $circleNode->setAttribute('cx', 200);
        $circleNode->setAttribute('cy', 200);
        $circleNode->setAttribute('r', 200);
        $circleNode->setAttribute('fill', 'red');

        $document->documentElement->appendChild($circleNode);
        $this->assertEqualXML($document);
    }

    public function testMagicSetter()
    {
        $strokeWidth = 10;
        $opacity = 0.2;
        $fill = 'cyan';

        $this->circle->strokeWidth = $strokeWidth;
        $this->circle->fillOpacity = $opacity;
        $this->circle->fill = $fill;

        $this->svgObj->append($this->circle);

        $document = $this->getDocument();
        $circleNode = $document->createElement('circle');
        $circleNode->setAttribute('cx', 200);
        $circleNode->setAttribute('cy', 200);
        $circleNode->setAttribute('r', 200);
        $circleNode->setAttribute('stroke-width', $strokeWidth);
        $circleNode->setAttribute('fill-opacity', $opacity);
        $circleNode->setAttribute('fill', $fill);

        $document->documentElement->appendChild($circleNode);
        $this->assertEqualXML($document);
    }

    public function testAllAttributes()
    {
        $attributes = [
            'class'       => 'cls',
            'stroke'      => 'red',
            'strokeWidth' => 2.3,
        ];

        foreach ($attributes as $attribute => $value) {
            $this->circle->{$attribute} = $value;
        }

        self::assertEquals(['cy' => 200, 'r' => 200, 'class' => 'cls', 'stroke' => 'red', 'stroke-width' => 2.3], $this->circle->allAttributes(['cx']));
    }

    /**
     * @depends testAllAttributes
     */
    public function testApply()
    {
        $data = [
            'viewBox'     => '0 0 800 600',
            'strokeWidth' => 10,
            'stroke'      => 'cyan',
            'x'           => 10,
            'y'           => 50,
            'cy'          => 500,
            'cx'          => 800,
            'version'     => '1.1',
        ];

        $this->circle->apply($data);

        $data['stroke-width'] = $data['strokeWidth'];
        unset($data['strokeWidth']);

        self::assertEquals($data, $this->circle->allAttributes(['r']));
    }

    public function testAddAndGetName()
    {
        self::assertEquals('svg', $this->svgObj->getName());
        $group = new G($this->svgObj);
        self::assertEquals('g', $group->getName());
    }

    public function testIdAttributeSetter()
    {
        $path = new Path($this->svgObj, 0, 0);

        $path->id = null; //will generate id for Path element.
        self::assertNotNull($path->id);
        self::assertStringStartsWith("__" . $path->getName(), $path->id);

        foreach ([null, '', false] as $value) {
            $path->stroke = $value;
            self::assertNull($path->stroke);
        }
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testCopyException()
    {
        $this->svgObj->copy();
    }

    public function testToString()
    {
        ob_start();

        echo $this->svgObj;

        $svgString = ob_get_contents();

        ob_end_clean();

        self::assertEquals($svgString, $this->svgObj->draw());
    }

    public function testStringOutput()
    {
        $svgz = gzencode($this->svgObj->draw(), 9);
        $svgBase64 = "data:image/svg+xml;base64," . base64_encode($this->svgObj->draw());

        self::assertEquals(htmlspecialchars($this->svgObj->draw()), $this->svgObj->asString());
        self::assertEquals($this->svgObj->draw(), $this->svgObj->asSVG());

        self::assertEquals($svgz, $this->svgObj->asSVGZ());
        self::assertEquals($svgBase64, $this->svgObj->asDataUriBase64());

    }

    public function testElementsNames()
    {
        $svg = new SVG();

        $path = new Path($svg, 0, 0);
        $mPath = new MPath($svg, $path);

        /** @var ElementInterface[] $data */
        $data = [
                'animate' => new Animate($svg, 'width', 0, 0, 0),
                'mpath' => $mPath,
                'animateMotion' => new AnimateMotion($svg, $mPath),
                'path' => $path,
                'set' => new Set($svg),
                'a' => new A($svg),
                'defs' => new Defs($svg),
                'g' => new G($svg),
                'marker' => new Marker($svg),
                'mask' => new Mask($svg),
                'pattern' => new Pattern($svg),
                'switch' => new Swtch($svg),
                'symbol' => new Symbol($svg),
                'desc' => new Desc($svg, 'value'),
                'metadata' => new Metadata($svg, 'value'),
                'title' => new Title($svg, 'value'),
                'filter' => new Filter($svg),
                'feBlend' => new Blend($svg),
                'feColorMatrix' => new ColorMatrix($svg),
                'feComponentTransfer' => new ComponentTransfer($svg),
                'feComposite' => new Composite($svg),
                'feConvolveMatrix' => new ConvolveMatrix($svg),
                'feDiffuseLighting' => new DiffuseLighting($svg),
                'feDisplacementMap' => new DisplacementMap($svg),
                'feFlood' => new Flood($svg),
                'feFuncA' => new FuncA($svg, 'table'),
                'feFuncB' => new FuncB($svg, 'table'),
                'feFuncG' => new FuncG($svg, 'table'),
                'feFuncR' => new FuncR($svg, 'table'),
                'feGaussianBlur' => new GaussianBlur($svg),
                'image' => new Image($svg),
                'feMerge' => new Merge($svg),
                'feMergeNode' => new MergeNode($svg),
                'feMorphology' => new Morphology($svg),
                'feOffset' => new Offset($svg),
                'feSpecularLighting' => new SpecularLighting($svg),
                'feTile' => new Tile($svg),
                'feTurbulence' => new Turbulence($svg),
                'font' => new Font($svg),
                'font-face' => new FontFace($svg),
                'font-face-format' => new FontFaceFormat($svg),
                'font-face-name' => new FontFaceName($svg),
                'font-face-src' => new FontFaceSrc($svg),
                'font-face-uri' => new FontFaceUri($svg),
                'glyph' => new Glyph($svg),
                'missing-glyph' => new MissingGlyph($svg),
                'hkern' => new HKern($svg),
                'vkern' => new VKern($svg),
                'linearGradient' => new LinearGradient($svg),
                'radialGradient' => new RadialGradient($svg),
                'stop' => new Stop($svg),
                'feDistantLight' => new DistantLight($svg),
                'fePointLight' => new PointLight($svg),
                'feSpotLight' => new SpotLight($svg),
                'circle' => new Circle($svg, 0, 0, 0),
                'ellipse' => new Ellipse($svg, 0, 0, 0, 0),
                'line' => new Line($svg),
                'polygon' => new Polygon($svg),
                'polyline' => new Polyline($svg),
                'rect' => new Rect($svg, 0, 0),
                'altGlyph' => new AltGlyph($svg, 0, 0),
                'altGlyphDef' => new AltGlyphDef($svg, 0, 0),
                'altGlyphItem' => new AltGlyphItem($svg, 0, 0),
                'glyphRef' => new GlyphRef($svg, 0, 0),
                'text' => new Text($svg, 'text'),
                'textPath' => new TextPath($svg),
                'tref' => new TRef($svg),
                'tspan' => new TSpan($svg),
        ];

        foreach ($data as $name => $obj) {
            self::assertEquals($name, $obj->getName());
            self::assertInstanceOf('nstdio\svg\SVGElement', $obj);
        }

        self::assertEquals('svg', $svg->getName());
    }
}
