<?php
namespace nstdio\svg\xml;
use DOMElement;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\XMLDocumentInterface;

/**
 * Class DOMElementWrapper
 *
 * @package nstdio\svg\xml
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class DOMElementWrapper implements XMLDocumentInterface
{
    use ElementTrait;

    /**
     * @var DOMElement
     */
    private $element;


    /**
     * DOMElementWrapper constructor.
     *
     * @param DOMElement $element
     */
    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    /**
     * @inheritdoc
     */
    public function setAttribute($name, $value = null)
    {
        $this->element->setAttribute($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function getAttribute($name)
    {
        return $this->element->getAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function setAttributeNS($namespaceURI, $qualifiedName, $value)
    {
        $this->element->setAttributeNS($namespaceURI, $qualifiedName, $value);
    }

    /**
     * @inheritdoc
     */
    public function getAttributeNS($namespaceURI, $localName)
    {
        return $this->element->getAttributeNS($namespaceURI, $localName);
    }

    /**
     * @inheritdoc
     */
    public function appendChild(XMLDocumentInterface $newNode)
    {
        $this->element->appendChild($newNode->getElement());
    }

    /**
     * @inheritdoc
     */
    public function createElement($name, $value = null)
    {

    }

    /**
     * @inheritdoc
     */
    public function createElementNS($namespaceURI, $qualifiedName, $value = null)
    {
        throw NotImplementedException::newInstance();
    }

    /**
     * @inheritdoc
     */
    public function saveHTML()
    {
        // TODO: Implement saveHTML() method.
    }

    /**
     * @inheritdoc
     */
    public function saveXML($formatOutput)
    {
        // TODO: Implement saveXML() method.
    }

    /**
     * @inheritdoc
     */
    public static function isLoaded()
    {
        return DOMWrapper::isLoaded();
    }

    /**
     * @inheritdoc
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @inheritdoc
     */
    public function setNodeValue($value)
    {
        $this->element->nodeValue = $value;
    }

    /**
     * @inheritdoc
     */
    public function getNodeValue()
    {
        return $this->element->nodeValue;
    }

    /**
     * @inheritdoc
     */
    public function removeNode(XMLDocumentInterface $child)
    {
        return $this->element->removeChild($child->getElement());
    }
}