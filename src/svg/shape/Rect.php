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
    /**
     * Rect constructor.
     *
     * @param ElementInterface $parent
     * @param                  $height
     * @param                  $width
     * @param int              $x
     * @param int              $y
     */
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'rect';
    }

    /**
     * @param array $rect1
     * @param array $rect2
     *
     * @return array
     */
    public static function union(array $rect1, array $rect2)
    {
        $result = [];
        $result['x'] = min($rect1['x'], $rect2['x']);
        $result['y'] = min($rect1['y'], $rect2['y']);

        $rect1Width = $rect1['x'] + $rect1['width'] - min($rect1['x'], $rect2['x']);
        $rect2Width = $rect2['x'] + $rect2['width'] - min($rect1['x'], $rect2['x']);


        $rect1Height = $rect1['y'] + $rect1['height'] - min($rect1['y'], $rect2['y']);
        $rect2Height = $rect2['y'] + $rect2['height'] - min($rect1['y'], $rect2['y']);

        $result['width'] = max($rect1Width, $rect2Width);
        $result['height'] = max($rect1Height, $rect2Height);

        return $result;
    }

    /**
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     *
     * @return array
     */
    public static function boxFromPoints($x1, $y1, $x2, $y2)
    {
        return [
            'width'  => max($x1, $x2) - min($x1, $x2),
            'height' => max($y1, $y2) - min($y1, $y2),
            'x'      => min($x1, $x2),
            'y'      => min($y1, $y2),
        ];
    }

    /**
     * @return float
     */
    public function getCenterX()
    {
        return $this->x + ($this->width / 2);
    }

    /**
     * @return float
     */
    protected function getCenterY()
    {
        return $this->y + ($this->width / 2);
    }

    /**
     * @return array
     */
    public function getBoundingBox()
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'x' => $this->x,
            'y' => $this->y,
        ];
    }
}