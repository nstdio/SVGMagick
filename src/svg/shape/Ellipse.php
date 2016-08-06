<?php
namespace nstdio\svg\shape;

use nstdio\svg\ElementInterface;

/**
 * Class Ellipse
 * The 'ellipse' element defines an ellipse which is axis-aligned with the current user coordinate system based on a
 * center point and two radii.
 *
 * @link    https://www.w3.org/TR/SVG11/shapes.html#EllipseElement
 *
 * @property float rx
 * @property float $ry
 *
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Ellipse extends RoundedShape
{
    public function __construct(ElementInterface $parent, $cx, $cy, $rx, $ry)
    {
        parent::__construct($parent, $cx, $cy);

        $this->rx = $rx;
        $this->ry = $ry;
    }

    public function getName()
    {
        return 'ellipse';
    }
}