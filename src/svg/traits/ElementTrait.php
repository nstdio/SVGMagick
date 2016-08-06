<?php
namespace nstdio\svg\traits;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;
use nstdio\svg\SVGElement;
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
     * @param ElementInterface|ElementInterface[] $elements
     *
     * @return $this
     */
    public function append(ElementInterface $elements)
    {
        /** @var ElementInterface[] $elements */
        $elements = array_filter(func_get_args(), function ($item) {
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

    public function getChild($name)
    {
        $find = [];
        /** @var ContainerInterface $item */
        foreach ($this->child as $item) {
            if (strtolower($item->getName()) === strtolower($name)) {
                $find[] = $item;
            }
            if ($item->hasChild()) {
                $find = array_merge($find, $item->getChild($name));
            }
        }

        return $find;
    }

    public function getChildById($id)
    {
        if (!$this->hasChild()) {
            return null;
        }
        /** @var ContainerInterface|SVGElement $item */
        foreach ($this->child as $item) {
            if ($item->id === $id) {
                return $item;
            }
            if ($item->hasChild()) {
                return $item->getChildById($id);
            }
        }
    }

    public function hasChild()
    {
        return count($this->child) > 0;
    }
}