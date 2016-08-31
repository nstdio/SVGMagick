<?php
namespace nstdio\svg\xml;

use nstdio\svg\util\KeyValueWriter;
use nstdio\svg\XMLDocumentInterface;

/**
 * Class DOMNode
 *
 * @package nstdio\svg\xml
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class DOMNode implements XMLDocumentInterface
{
    /**
     * @inheritdoc
     */
    abstract public function setNodeValue($value);

    /**
     * @inheritdoc
     */
    abstract public function getNodeValue();

    /**
     * @inheritdoc
     */
    public function attributes(array $except = [])
    {
        return KeyValueWriter::allAttributes($this->getElement(), $except);
    }

    /**
     * @inheritdoc
     */
    abstract public function getElement();

    /**
     * @inheritdoc
     */
    public function setAttribute($name, $value = null)
    {
        $this->getElement()->setAttribute($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function getAttribute($name)
    {
        return $this->getElement()->getAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function setAttributeNS($namespaceURI, $qualifiedName, $value)
    {
        $this->getElement()->setAttributeNS($namespaceURI, $qualifiedName, $value);
    }

    /**
     * @inheritdoc
     */
    public function getAttributeNS($namespaceURI, $localName)
    {
        return $this->getElement()->getAttributeNS($namespaceURI, $localName);
    }

    public function appendChild(XMLDocumentInterface $newNode)
    {
        $this->getElement()->appendChild($newNode->getElement());
    }

    /**
     * @inheritdoc
     */
    public function createElement($name, $value = null)
    {
        // on PHP 5.4.* createElement method with null parameter will create empty DOMText object as child of created element
        $element = $value === null ? $this->getElement()->createElement($name) : $this->getElement()->createElement($name, $value);

        return new DOMElementWrapper($element);
    }

    /**
     * @inheritdoc
     */
    public function createElementNS($namespaceURI, $qualifiedName, $value = null)
    {
        return $this->getElement()->createElementNS($namespaceURI, $qualifiedName, $value);
    }

    /**
     * @inheritdoc
     */
    public function removeNode(XMLDocumentInterface $child)
    {
        return $this->getElement()->removeChild($child->getElement());
    }

    /**
     * @inheritdoc
     */
    public function saveHTML()
    {
        return $this->getElement()->saveHTML();
    }

    public function saveXML($formatOutput)
    {
        return $this->getElement()->saveXML();
    }

    /**
     * @inheritdoc
     */
    public static function isLoaded()
    {
        return extension_loaded('dom');
    }

    public function __debugInfo()
    {
        return [
            'nodeName' => $this->getElement()->nodeName,
        ];
    }
}