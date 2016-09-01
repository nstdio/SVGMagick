<?php
namespace nstdio\svg\traits;


use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;
use nstdio\svg\ElementStorage;
use nstdio\svg\SVGElement;
use nstdio\svg\XMLDocumentInterface;

/**
 * Class ChildTrait
 *
 * @package nstdio\svg\traits
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
trait ChildTrait
{
    /**
     * @var ElementStorage
     */
    protected $child;

    /**
     * @param string $name
     *
     * @return ContainerInterface[]
     */
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

    /**
     * @param $id
     *
     * @return ContainerInterface
     */
    public function getChildById($id)
    {
        $find = null;
        /** @var ContainerInterface|SVGElement $item */
        foreach ($this->child as $item) {
            if ($item->id === $id) {
                $find = $item;
            }
        }
        if ($find === null) {
            foreach ($this->child as $item) {
                if ($item->hasChild()) {
                    $find = $item->getChildById($id);
                }
            }
        }

        return $find;
    }

    /**
     * @return ContainerInterface|null
     */
    public function getFirstChild()
    {
        if (!$this->hasChild()) {
            return null;
        }

        return $this->child[0];
    }

    /**
     * @return bool
     */
    public function hasChild()
    {
        return count($this->child) > 0;
    }

    /**
     * @return ContainerInterface[]|ElementStorage
     */
    public function getChildren()
    {
        return $this->child;
    }

    /**
     * @param $index
     *
     * @return ContainerInterface|SVGElement|null
     */
    public function getChildAtIndex($index)
    {
        return $this->child[$index];
    }

    /**
     * @param ElementInterface $child
     */
    public function removeChild(ElementInterface $child)
    {
        $this->child->remove($child);
        /** @var XMLDocumentInterface $element */
        $element = $this->getElement();
        if ($element instanceof \DOMNode) {
            $element->removeChild($child->getElement()->getElement());
        } else {
            $element->removeNode($child->getElement());
        }
    }
}