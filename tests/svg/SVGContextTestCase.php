<?php
use nstdio\svg\Base;
use nstdio\svg\container\SVG;

/**
 * Class TestCase
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class SVGContextTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var SVG
     */
    protected $svgObj;

    protected $width;
    protected $height;

    public function setUp()
    {
        $this->width = 100;
        $this->height = 200;
        $this->svgObj = new SVG($this->width, $this->height);
    }

    /**
     * @return DOMDocument
     */
    protected function getDocument()
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $svgNode = $document->createElement('svg');
        $svgNode->setAttribute('width', $this->width);
        $svgNode->setAttribute('height', $this->height);
        $svgNode->setAttribute('version', '1.1');
        $svgNode->setAttribute('xmlns', Base::XML_NS);
        $svgNode->setAttribute('viewBox', "0 0 {$this->width} {$this->height}");

        $svgNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', Base::XML_NS_XLINK);
        $document->appendChild($svgNode);
        $svgNode->appendChild($document->createElement('defs'));

        return $document;
    }

    /**
     * @param DOMDocument $document
     */
    protected function assertEqualXML(DOMDocument $document)
    {
        self::assertEqualXMLStructure($document->documentElement, $this->svgObj->getElement(), true);
        self::assertEquals($document->saveHTML(), $this->svgObj->draw());
    }
}