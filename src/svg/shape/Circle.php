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

    public static function create(ElementInterface $parent, $cx, $cy, $r)
    {
        return new Circle($parent, $cx, $cy, $r);
    }

    public function getName()
    {
        return 'circle';
    }

    public function getBoundingBox()
    {
        $r2 = 2 * $this->r;
        return [
            'width' => $r2,
            'height' => $r2,
            'x' => abs($this->cx - $this->r),
            'y' => abs($this->cx - $this->r),
        ];
    }
}