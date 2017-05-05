<?php
namespace nstdio\svg\shape;

use nstdio\svg\ElementInterface;


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
    /**
     * @param ElementInterface $parent
     * @param array            $coordinatePairs
     *
     * @return Polygon
     */
    public static function createWithPairs(ElementInterface $parent, array $coordinatePairs)
    {
        return self::create($parent)->addPointArray($coordinatePairs);
    }

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
     * @param ElementInterface $parent
     *
     * @return Polygon
     */
    public static function create(ElementInterface $parent)
    {
        return new Polygon($parent);
    }

    /**
     * @param ElementInterface $parent
     * @param                  $x
     * @param                  $y
     *
     * @return Polygon
     */
    public static function createWithPoint(ElementInterface $parent, $x, $y)
    {
        return self::create($parent)->addPoint($x, $y);
    }

    /**
     * @return Path | null
     */
    public function toPath()
    {
        $pts = $this->pointsAsArray();
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

    /**
     * @return array
     */
    private function pointsAsArray()
    {
        $pts = [];
        foreach (explode(' ', $this->points) as $item) {
            $tmp = explode(',', $item);
            if (count($tmp) !== 2) {
                continue;
            }
            $pts[] = $tmp;
        }

        return $pts;
    }

    public function getName()
    {
        return 'polygon';
    }

    protected function getCenterX()
    {
        return $this->getBoundingBox()['width'] / 2;
    }

    public function getBoundingBox()
    {
        $x1 = $y1 = PHP_INT_MAX;
        $x2 = $y2 = -PHP_INT_MAX;

        foreach ($this->pointsAsArray() as $value) {
            $x1 = min($x1, $value[0]);
            $x2 = max($x2, $value[0]);
            $y1 = min($y1, $value[1]);
            $y2 = max($y2, $value[1]);
        }

        return Rect::boxFromPoints($x1, $y1, $x2, $y2);
    }

    protected function getCenterY()
    {
        return $this->getBoundingBox()['height'] / 2;
    }
}