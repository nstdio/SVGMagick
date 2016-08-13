<?php
namespace nstdio\svg\shape;




/**
 * Class Polygon
 *
 * The 'polygon' element defines a closed shape consisting of a set of connected straight line segments.
 *
 * @link    https://www.w3.org/TR/SVG11/shapes.html#PolygonElement
 * @property string $points The points that make up the polygon. All coordinate values are in the user coordinate system.
 *
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Polygon extends Shape
{
    public function addPointArray(array $coordinatePairs)
    {
        foreach ($coordinatePairs as $pair) {

            if (!is_array($pair) || count($pair) !== 2) {
                continue;
            }
            $pair = array_values($pair);
            $this->addPoint($pair[0], $pair[1]);
        }

        return $this;
    }

    public function addPoint($x, $y)
    {
        if ($this->points === null) {
            $this->points = "$x,$y";
        } else {
            $this->points .= " $x,$y";
        }

        return $this;
    }

    /**
     * @return Path | null
     */
    public function toPath()
    {
        $pts = [];

        foreach (explode(' ', $this->points) as $item) {
            $tmp = explode(',', $item);
            if (count($tmp) !== 2) {
                continue;
            }
            $pts[] = $tmp;
        }
        if (!empty($pts)) {
            $path = new Path($this->getRoot(), $pts[0][0], $pts[0][1]);
            unset($pts[0]);
            foreach ($pts as $point) {
                $path->lineTo($point[0], $point[1]);
            }
            return $path;
        }
        return null;
    }

    public function getName()
    {
        return 'polygon';
    }

    protected function getCenterX()
    {
        // TODO: Implement getCenterX() method.
    }

    protected function getCenterY()
    {
        // TODO: Implement getCenterY() method.
    }
}