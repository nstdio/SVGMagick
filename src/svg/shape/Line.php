<?php
namespace nstdio\svg\shape;

use nstdio\svg\ElementInterface;

/**
 * Class Line
 * The 'line' element defines a line segment that starts at one point and ends at another.
 *
 * @link    https://www.w3.org/TR/SVG11/shapes.html#LineElement
 * @property float x1 The x-axis coordinate of the start of the line. If the attribute is not specified, the effect is
 *           as if a value of "0" were specified.
 * @property float y1 The y-axis coordinate of the start of the line. If the attribute is not specified, the effect is
 *           as if a value of "0" were specified.
 * @property float x2 The x-axis coordinate of the end of the line. If the attribute is not specified, the effect is as
 *           if a value of "0" were specified.
 * @property float y2 The y-axis coordinate of the end of the line. If the attribute is not specified, the effect is as
 *           if a value of "0" were specified.
 *
 * @package shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Line extends Shape
{
    public function __construct(ElementInterface $parent, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0)
    {
        parent::__construct($parent);

        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->x2 = $x2;
        $this->y2 = $y2;
    }

    /**
     * @param bool $closePath
     *
     * @return Path
     */
    public function toPath($closePath = false)
    {
        $path = new Path($this->getRoot(), $this->x1, $this->y1);
        $path->lineTo($this->x2, $this->y2);

        if ($closePath === true) {
            $path->closePath();
        }

        return $path;
    }

    /**
     * @return Polygon
     */
    public function toPolygon()
    {
        $polygon = new Polygon($this->getRoot());
        $polygon->addPointArray([
            [$this->x1, $this->y1],
            [$this->x2, $this->y2],
        ]);

        return $polygon;
    }

    public function getName()
    {
        return 'line';
    }
}