<?php
namespace nstdio\svg\xml;

use DOMDocument;
use nstdio\svg\Base;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\XMLDocumentInterface;

/**
 * Class DomWrapper
 *
 * @package nstdio\svg\xml
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
final class DOMWrapper implements XMLDocumentInterface
{
    use ElementTrait;

    /**
     * @var DOMDocument
     */
    private $dom;

    public function __construct()
    {
        $this->dom = new DOMDocument(Base::DOM_VERSION, Base::DOM_ENCODING);
        $this->dom->xmlStandalone = false;
        $this->dom->formatOutput = true;
    }

    public function setAttribute($name, $value = null)
    {
        $this->dom->documentElement->setAttribute($name, $value);
    }

    public function getAttribute($name)
    {
        return $this->dom->documentElement->getAttribute($name);
    }

    public function setAttributeNS($namespaceURI, $qualifiedName, $value)
    {
        // TODO: Implement setAttributeNS() method.
    }

    public function getAttributeNS($namespaceURI, $localName)
    {
        // TODO: Implement getAttributeNS() method.
    }

    public function appendChild(XMLDocumentInterface $newNode)
    {
        return $this->dom->appendChild($newNode->getElement());
    }

    public function createElement($name, $value = null)
    {
        $element = $this->dom->createElement($name, $value);

        return new DOMElementWrapper($element);
    }

    public function createElementNS($namespaceURI, $qualifiedName, $value = null)
    {
        return $this->dom->createElementNS($namespaceURI, $qualifiedName, $value);
    }

    public static function isLoaded()
    {
        return extension_loaded('dom');
    }

    public function saveHTML()
    {
        return $this->dom->saveHTML();
    }

    public function getElement()
    {
        return $this->dom->documentElement;
    }

    /**
     * @param $value
     */
    public function setNodeValue($value)
    {
        throw new NotImplementedException();
    }
}