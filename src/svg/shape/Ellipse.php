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
    /**
     * Ellipse constructor.
     *
     * @param ElementInterface $parent
     * @param                  $cx
     * @param                  $cy
     * @param                  $rx
     * @param                  $ry
     */
    public function __construct(ElementInterface $parent, $cx, $cy, $rx, $ry)
    {
        parent::__construct($parent, $cx, $cy);

        $this->rx = $rx;
        $this->ry = $ry;
    }

    /**
     * @param ElementInterface $parent
     * @param                  $cx
     * @param                  $cy
     * @param                  $rx
     * @param                  $ry
     *
     * @return Ellipse
     */
    public static function create(ElementInterface $parent, $cx, $cy, $rx, $ry)
    {
        return new Ellipse($parent, $cx, $cy, $rx, $ry);
    }

    public function getName()
    {
        return 'ellipse';
    }

    public function getBoundingBox()
    {
        return [
            'width'  => 2 * $this->rx,
            'height' => 2 * $this->ry,
            'x'      => abs($this->cx - $this->rx),
            'y'      => abs($this->cy - $this->ry),
        ];
    }
}