<?php
namespace nstdio\svg\container;

use DOMElement;
use Mimey\MimeTypes;
use nstdio\svg\Base;
use nstdio\svg\ElementFactoryInterface;
use nstdio\svg\ElementStorage;
use nstdio\svg\import\Importer;
use nstdio\svg\output\IOFormat;
use nstdio\svg\output\Output;
use nstdio\svg\output\OutputInterface;
use nstdio\svg\output\SVGOutputInterface;
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
class SVG extends Base implements ContainerInterface, ElementFactoryInterface, SVGOutputInterface
{
    use ElementTrait, ChildTrait;

    /**
     * @var XMLDocumentInterface
     */
    private $svg;

    /**
     * @var OutputInterface
     */
    private $outputImpl;

    public function __construct($width = 640, $height = 480, XMLDocumentInterface $dom = null)
    {
        parent::__construct($dom);

        $this->svg = $this->element('svg');
        $this->child = new ElementStorage();
        $this->outputImpl = new Output();

        $this->apply([
            'width'   => $width,
            'height'  => $height,
            'version' => '1.1',
            'xmlns'   => self::XML_NS,
            'viewBox' => sprintf("0 0 %d %d", $width, $height),
        ]);

        $this->domImpl->appendChild($this->svg);

        $this->svg->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', self::XML_NS_XLINK);

        new Defs($this);
    }

    /**
     * @param array $assoc
     *
     * @return SVG
     */
    public function apply(array $assoc)
    {
        KeyValueWriter::apply($this->svg, $assoc);

        return $this;
    }

    /**
     * @param                           $width
     * @param                           $height
     * @param XMLDocumentInterface|null $dom
     *
     * @return SVG
     */
    public static function create($width, $height, XMLDocumentInterface $dom = null)
    {
        return new SVG($width, $height, $dom);
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

    /**
     * @inheritdoc
     */
    public function copy(array $apply = [], array $ignore = [], ContainerInterface $parent = null)
    {
        throw new \BadMethodCallException("You cannot copy SVG element.");
    }

    /**
     * Returns escaped svg as plain text.
     *
     * @return string
     */
    public function asString()
    {
        return htmlspecialchars($this->draw());
    }

    /**
     * @inheritdoc
     */
    public function asSVG()
    {
        return $this->draw();
    }

    /**
     * @inheritdoc
     */
    public function asSVGZ($sendHeader = false)
    {
        if ($sendHeader && !headers_sent()) {
            header("Content-Encoding: gzip");
        }

        return gzencode($this->draw(), 9);
    }

    /**
     * @inheritdoc
     */
    public function asDataUriBase64()
    {
        $encoded = base64_encode($this->draw());
        $mimeType = (new MimeTypes)->getMimeType('svg');

        return sprintf("data:%s;base64,%s", $mimeType, $encoded);
    }

    /**
     * @inheritdoc
     */
    public function asFile($name, $prettyPrint = false, $override = false)
    {
        return $this->outputImpl->file(
            $name,
            $this->domImpl->saveXML($prettyPrint),
            $override
        );
    }

    /**
     * @inheritdoc
     */
    public function asImageFile($name, $format = IOFormat::PNG, $override = false)
    {
        return $this->outputImpl->imageFile(
            $this->domImpl->saveXML(false),
            $name,
            $format,
            $override
        );
    }

    /**
     * @inheritdoc
     */
    public function asImage($format = IOFormat::PNG, $sendHeader = false)
    {
        return $this->outputImpl->image(
            $this->domImpl->saveXML(false),
            $format,
            $sendHeader
        );
    }

    public static function fromString($svgString)
    {
        return (new Importer)->fromString($svgString);
    }
}
