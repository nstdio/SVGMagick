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


    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    public function setAttribute($name, $value = null)
    {
        $this->element->setAttribute($name, $value);
    }

    public function getAttribute($name)
    {
        return $this->element->getAttribute($name);
    }

    public function setAttributeNS($namespaceURI, $qualifiedName, $value)
    {
        $this->element->setAttributeNS($namespaceURI, $qualifiedName, $value);
    }

    public function getAttributeNS($namespaceURI, $localName)
    {
        return $this->element->getAttributeNS($namespaceURI, $localName);
    }

    public function appendChild(XMLDocumentInterface $newNode)
    {
        $this->element->appendChild($newNode->getElement());
    }

    /**
     * @param      $name
     * @param null $value
     *
     * @return XMLDocumentInterface A new element.
     */
    public function createElement($name, $value = null)
    {

    }

    public function createElementNS($namespaceURI, $qualifiedName, $value = null)
    {
        throw NotImplementedException::newInstance();
    }

    public function saveHTML()
    {
        // TODO: Implement saveHTML() method.
    }

    /**
     * @return bool
     */
    public static function isLoaded()
    {
        return DOMWrapper::isLoaded();
    }

    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param $value
     */
    public function setNodeValue($value)
    {
        $this->element->nodeValue = $value;
    }
}