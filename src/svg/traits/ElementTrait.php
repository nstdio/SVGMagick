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
    /**
     * Multiply argument can be passed.
     *
     * @param ElementInterface $elements
     *
     * @return $this
     */
    public function append(ElementInterface $elements)
    {
        /** @var ElementInterface[] $elements */
        $elements = array_filter(func_get_args(), function($item) {
            return $item instanceof ElementInterface;
        });

        foreach ($elements as $element) {
            $this->child[] = $element;
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
}