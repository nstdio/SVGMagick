<?php
namespace nstdio\svg\traits;

use nstdio\svg\ElementInterface;
use nstdio\svg\XMLDocumentInterface;

/**
 * Trait ElementTrait
 *
 * @package nstdio\svg\traits
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
trait ElementTrait
{
    private $maxNestingLevel = 256;

    /**
     * @param ElementInterface[] ...$elements
     *
     * @return $this
     */
    public function append(ElementInterface ...$elements)
    {
        foreach ($elements as $element) {
            /** @var \DOMElement $e */
            $e = $this->getElement();
            if ($e instanceof \DOMNode) {
                $e->appendChild($element->getElement()->getElement());
            } elseif ($e instanceof XMLDocumentInterface) {
                $e->appendChild($element->getElement());
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attributes(array $except = [])
    {
        $attributes = [];
        for ($i = 0; $i < $this->getElement()->attributes->length; $i++) {
            $node = $this->getElement()->attributes->item($i);
            if (!in_array($node->nodeName, $except)) {
                $attributes[$node->nodeName] = $node->nodeValue;
            }
        }

        return $attributes;
    }
}