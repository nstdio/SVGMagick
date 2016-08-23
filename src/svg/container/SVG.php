<?php
namespace nstdio\svg\container;

use DOMElement;
use nstdio\svg\Base;
use nstdio\svg\ElementFactoryInterface;
use nstdio\svg\ElementStorage;
use nstdio\svg\traits\ChildTrait;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\util\KeyValueWriter;
use nstdio\svg\XMLDocumentInterface;

/**
 * Class SVG
 *
 * @property string viewBox
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SVG extends Base implements ContainerInterface, ElementFactoryInterface
{
    use ElementTrait, ChildTrait;

    /**
     * @var XMLDocumentInterface
     */
    private $svg;

    public function __construct($width = 640, $height = 480, XMLDocumentInterface $dom = null)
    {
        parent::__construct($dom);

        $this->svg = $this->element('svg');
        $this->child = new ElementStorage();

        $this->apply([
            'width' => $width,
            'height' => $height,
            'version' => '1.1',
            'xmlns' => self::XML_NS,
            'viewBox' => sprintf("0 0 %d %d", $width, $height)
        ]);

        $this->domImpl->appendChild($this->svg);

        $this->svg->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', self::XML_NS_XLINK);

        new Defs($this);
    }

    /**
     * @param array $assoc
     *
     * @return mixed
     */
    public function apply(array $assoc)
    {
        KeyValueWriter::apply($this->svg, $assoc);

        return $this;
    }

    public function getRoot()
    {
        return $this->svg;
    }

    public function getName()
    {
        return 'svg';
    }

    /**
     * @return DOMElement
     */
    public function getElement()
    {
        return $this->svg->getElement();
    }

    public function createElement($name, $value = null, $attributes = [])
    {
        $element = $this->domImpl->createElement($name, $value);

        KeyValueWriter::apply($element, $attributes);

        return $element;
    }

    public function __toString()
    {
        return $this->draw();
    }

    public function draw()
    {
        return $this->domImpl->saveHTML();
    }

    public function asBase64()
    {
        $data = base64_encode($this->domImpl->saveHTML());
        return "data:image/svg+xml;base64,{$data}";
    }
}