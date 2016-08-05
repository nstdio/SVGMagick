<?php
namespace nstdio\svg\shape;

/**
 * Class Polyline
 * The 'polyline' element defines a set of connected straight line segments. Typically, 'polyline' elements define open
 * shapes.
 *
 * @link    https://www.w3.org/TR/SVG11/shapes.html#PolylineElement
 * @package shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Polyline extends Polygon
{

    public function getName()
    {
        return 'polyline';
    }
}