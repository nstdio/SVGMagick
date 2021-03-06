<?php
namespace nstdio\svg\shape;



use nstdio\svg\ElementInterface;

/**
 * Class RoundedShape
 * @property float $cx The capacitor for x axis
 * @property float $cy The capacitor for y axis
 * @package shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class RoundedShape extends Shape
{
    public function __construct(ElementInterface $parent, $cx, $cy)
    {
        parent::__construct($parent);

        $this->cx = $cx;
        $this->cy = $cy;
    }

    protected function getCenterX()
    {
        return $this->cx;
    }

    protected function getCenterY()
    {
        return $this->cy;
    }
}