<?php
namespace nstdio\svg;


interface XMLDocumentInterface
{
    public function getElement();

    /**
     * @param $value
     */
    public function setNodeValue($value);

    /**
     * @param array $except
     *
     * @return array Associative array where the keys are the name of the attribute node.
     */
    public function attributes(array $except);

    public function setAttribute($name, $value = null);

    public function getAttribute($name);

    public function setAttributeNS($namespaceURI, $qualifiedName, $value);

    public function getAttributeNS($namespaceURI, $localName);

    public function appendChild(XMLDocumentInterface $newNode);

    /**
     * @param      $name
     * @param null $value
     *
     * @return XMLDocumentInterface A new element.
     */
    public function createElement($name, $value = null);

    public function createElementNS($namespaceURI, $qualifiedName, $value = null);

    /**
     * @return string
     */
    public function saveHTML();

    /**
     * @return bool
     */
    public static function isLoaded();

}