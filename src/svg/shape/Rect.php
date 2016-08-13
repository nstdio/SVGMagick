<?php
namespace nstdio\svg\shape;

use nstdio\svg\ElementInterface;

/**
 * Class Rect
 * The 'rect' element defines a rectangle which is axis-aligned with the current user coordinate system. Rounded
 * rectangles can be achieved by setting appropriate values for attributes 'rx' and 'ry'.
 *
 * @link    https://www.w3.org/TR/SVG11/shapes.html#RectElement
 * @property float rx For rounded rectangles, the x-axis radius of the ellipse used to round off the corners of the
 *           rectangle. A negative value is an error
 * @property float ry For rounded rectangles, the y-axis radius of the ellipse used to round off the corners of the
 *           rectangle. A negative value is an error
 * @property float x  The x-axis coordinate of the side of the rectangle which has the smaller x-axis coordinate value
 *           in the current user coordinate system. If the attribute is not specified, the effect is as if a value of
 *           "0" were specified.
 * @property float y  The y-axis coordinate of the side of the rectangle which has the smaller y-axis coordinate value
 *           in the current user coordinate system. If the attribute is not specified, the effect is as if a value of
 *           "0" were specified.
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Rect extends Shape
{
    public function __construct(ElementInterface $parent, $height, $width, $x = 0, $y = 0)
    {
        parent::__construct($parent);

        $this->height = $height;
        $this->width = $width;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param float $radius
     */
    public function setBorderRadius($radius)
    {
        $this->rx = $radius;
        $this->ry = $radius;
    }

    /**
     * @return Path
     */
    public function toPath()
    {
        $path = new Path($this->getRoot(), $this->x + $this->rx, $this->y);
        list($x, $y, $width, $height, $rx, $ry) = [$this->x, $this->y, $this->width, $this->height, $this->rx, $this->ry];
        if ($rx === null) {
            $rx = 0;
        }
        if ($ry === null) {
            $ry = 0;
        }
        $path->hLineTo($x + $width - $rx)
            ->arcTo($rx, $ry, 0, false, true, $x + $width, $y + $ry)
            ->lineTo($x + $width, $y + $height - $ry)
            ->arcTo($rx, $ry, 0, false, true, $x + $width - $rx, $y + $height)
            ->lineTo($x + $rx, $y + $height)
            ->arcTo($rx, $ry, 0, false, true, $x, $y + $height - $ry)
            ->lineTo($x, $y + $ry)
            ->arcTo($rx, $ry, 0, false, true, $x + $rx, $y);

        $attributes = $this->allAttributes(['width', 'height', 'x', 'y', 'rx', 'ry']);

        foreach ($attributes as $key => $value) {
            $path->{$key} = $value;
        }

        return $path;
    }

    public function getName()
    {
        return 'rect';
    }

    public function getCenterX()
    {
        return $this->x + ($this->width / 2);
    }

    protected function getCenterY()
    {
        return $this->y + ($this->width / 2);
    }
}