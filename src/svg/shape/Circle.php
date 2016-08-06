<?php
namespace nstdio\svg\shape;

use nstdio\svg\ElementInterface;

/**
 * Class Circle
 *
 * @property float $r The circle radius.
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Circle extends RoundedShape
{
    public function __construct(ElementInterface $parent, $cx, $cy, $r)
    {
        parent::__construct($parent, $cx, $cy);

        $this->r = $r;
    }

    public function getName()
    {
        return 'circle';
    }
}