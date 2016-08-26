<?php
namespace nstdio\svg;


interface XMLDocumentInterface
{
    public function getElement();

    /**
     * @param mixed $value The text value of node.
     */
    public function setNodeValue($value);

    /**
     * @return string The text value of node.
     */
    public function getNodeValue();

    /**
     * @param array $except Returned array will not contain attributes specified in this array.
     *
     * @return array Associative array where the keys are the name of the attribute node.
     */
    public function attributes(array $except);

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute($name, $value = null);

    /**
     * @param string $name Attribute name.
     * @return string Attribute value.
     */
    public function getAttribute($name);

    /**
     * @param $namespaceURI
     * @param $qualifiedName
     * @param $value
     * @return string
     */
    public function setAttributeNS($namespaceURI, $qualifiedName, $value);

    /**
     * @param $namespaceURI
     * @param $localName
     * @return mixed
     */
    public function getAttributeNS($namespaceURI, $localName);

    /**
     * @param XMLDocumentInterface $newNode
     * @return void
     */
    public function appendChild(XMLDocumentInterface $newNode);

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return XMLDocumentInterface A new element.
     */
    public function createElement($name, $value = null);

    /**
     * @param string $namespaceURI
     * @param string $qualifiedName
     * @param string $value
     * @return mixed
     */
    public function createElementNS($namespaceURI, $qualifiedName, $value = null);

    /**
     * @param XMLDocumentInterface $child The element to remove.
     *
     * @return mixed
     */
    public function removeNode(XMLDocumentInterface $child);

    /**
     * @return string
     */
    public function saveHTML();

    /**
     * @return bool
     */
    public static function isLoaded();

}