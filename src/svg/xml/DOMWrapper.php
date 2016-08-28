<?php
namespace nstdio\svg\xml;

use DOMDocument;
use nstdio\svg\Base;

/**
 * Class DomWrapper
 *
 * @package nstdio\svg\xml
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
final class DOMWrapper extends DOMNode
{
    /**
     * @var DOMDocument
     */
    private $dom;

    /**
     * DOMWrapper constructor.
     */
    public function __construct()
    {
        $this->dom = new DOMDocument(Base::DOM_VERSION, Base::DOM_ENCODING);
        $this->dom->xmlStandalone = false;
    }

    /**
     * @inheritdoc
     */
    public function saveXML($formatOutput = false)
    {
        $this->dom->formatOutput = (bool) $formatOutput;

        return trim(parent::saveXML($formatOutput));
    }

    /**
     * @inheritdoc
     */
    public function getElement()
    {
        return $this->dom;
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
}