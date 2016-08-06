<?php
namespace nstdio\svg\container;

use nstdio\svg\ElementInterface;

interface ContainerInterface extends ElementInterface
{
    public function append(ElementInterface $elements);

    public function getFirstChild();

    public function getChild($name);

    public function getChildById($id);

    /**
     * @return bool
     */
    public function hasChild();
}