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
    }

    /**
     * @inheritdoc
     */
    public function setAttribute($name, $value = null)
    {
        $this->dom->documentElement->setAttribute($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function getAttribute($name)
    {
        return $this->dom->documentElement->getAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function setAttributeNS($namespaceURI, $qualifiedName, $value)
    {
        // TODO: Implement setAttributeNS() method.
    }

    /**
     * @inheritdoc
     */
    public function getAttributeNS($namespaceURI, $localName)
    {
        // TODO: Implement getAttributeNS() method.
    }

    /**
     * @inheritdoc
     */
    public function appendChild(XMLDocumentInterface $newNode)
    {
        return $this->dom->appendChild($newNode->getElement());
    }

    /**
     * @inheritdoc
     */
    public function createElement($name, $value = null)
    {
        // on PHP 5.4.* createElement method with null parameter will create empty DOMText object as child of created element
        $element = $value === null ? $this->dom->createElement($name) : $this->dom->createElement($name, $value);

        return new DOMElementWrapper($element);
    }

    /**
     * @inheritdoc
     */
    public function createElementNS($namespaceURI, $qualifiedName, $value = null)
    {
        return $this->dom->createElementNS($namespaceURI, $qualifiedName, $value);
    }

    /**
     * @inheritdoc
     */
    public static function isLoaded()
    {
        return extension_loaded('dom');
    }

    /**
     * @inheritdoc
     */
    public function saveHTML()
    {
        return $this->dom->saveHTML();
    }

    /**
     * @inheritdoc
     */
    public function saveXML($formatOutput = false)
    {
        $this->dom->formatOutput = (bool) $formatOutput;

        return trim($this->dom->saveXML());
    }

    /**
     * @inheritdoc
     */
    public function getElement()
    {
        return $this->dom->documentElement;
    }

    /**
     * @inheritdoc
     */
    public function setNodeValue($value)
    {
        throw NotImplementedException::newInstance();
    }

    /**
     * @inheritdoc
     */
    public function getNodeValue()
    {
        throw NotImplementedException::newInstance();
    }

    /**
     * @inheritdoc
     */
    public function removeNode(XMLDocumentInterface $child)
    {
        return $this->dom->documentElement->removeChild($child->getElement());
    }
}