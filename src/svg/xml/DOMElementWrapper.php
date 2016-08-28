<?php
namespace nstdio\svg\xml;

use DOMElement;

/**
 * Class DOMElementWrapper
 *
 * @package nstdio\svg\xml
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class DOMElementWrapper extends DOMNode
{
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
}
