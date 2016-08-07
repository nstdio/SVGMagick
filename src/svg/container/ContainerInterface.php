<?php
namespace nstdio\svg\container;

use nstdio\svg\ElementInterface;
use nstdio\svg\SVGElement;

interface ContainerInterface extends ElementInterface
{
    public function append(ElementInterface $elements);

    /**
     * @return ContainerInterface|null
     */
    public function getFirstChild();

    /**
     * @param $index
     *
     * @return ContainerInterface|SVGElement|null
     */
    public function getChildAtIndex($index);

    /**
     * @return ContainerInterface[]
     */
    public function getChildren();

    /**
     * @param $name
     *
     * @return ContainerInterface[]
     */
    public function getChild($name);

    /**
     * @param $id
     *
     * @return ContainerInterface
     */
    public function getChildById($id);

    /**
     * @return bool
     */
    public function hasChild();

    /**
     * @param ElementInterface $child
     *
     * @return void
     */
    public function removeChild(ElementInterface $child);
}