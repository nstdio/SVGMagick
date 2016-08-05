<?php
namespace nstdio\svg\container;

use nstdio\svg\ElementInterface;

interface ContainerInterface extends ElementInterface
{
    public function append(ElementInterface $elements);
}