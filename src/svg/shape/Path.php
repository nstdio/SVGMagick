<?php
namespace nstdio\svg\shape;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;
use nstdio\svg\traits\ElementTrait;

/**
 * Class Path
 *
 * @property string $d This attribute defines a path to follow. {@link
 *           https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/d}
 * @package shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Path extends Shape implements ContainerInterface
{
    use ElementTrait;

    /**
     * @var  PathBounds
     */
    private $boundingBox;

    /**
     * Path constructor.
     *
     * @param ElementInterface $parent
     * @param float            $x
     * @param float            $y
     * @param bool             $absolute
     */
    public function __construct(ElementInterface $parent, $x, $y, $absolute = true)
    {
        parent::__construct($parent);

        $this->boundingBox = new PathBounds();
        $this->moveTo($x, $y, $absolute);
    }

    /**
     * Start a new sub-path at the given (x,y) coordinate. M (uppercase) indicates that absolute coordinates will
     * follow; m (lowercase) indicates that relative coordinates will follow. If a moveto is followed by multiple pairs
     * of coordinates, the subsequent pairs are treated as implicit lineto commands. Hence, implicit lineto commands
     * will be relative if the moveto is relative, and absolute if the moveto is absolute. If a relative moveto (m)
     * appears as the first element of the path, then it is treated as a pair of absolute coordinates. In this case,
     * subsequent pairs of coordinates are treated as relative even though the initial moveto is interpreted as an
     * absolute moveto.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataMovetoCommands
     *
     * @param float $x The absolute (relative) X coordinate for the end point of this path segment.
     * @param float $y The absolute (relative) Y coordinate for the end point of this path segment.
     * @param bool  $absolute
     *
     * @return $this
     */
    public function moveTo($x, $y, $absolute = true)
    {
        $this->checkFirstModifier();

        $modifier = $absolute ? 'M' : 'm';
        $this->d = "$modifier $x, $y";

        $this->boundingBox->addData($modifier, [$x, $y]);

        return $this;
    }

    /**
     * @param string $type
     */
    private function buildPath($type)
    {
        $params = array_slice(func_get_args(), 1);
        $this->boundingBox->addData($type, $params);

        $this->d .= " $type";
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $this->addArrayToPath($value);
            } else {
                if ($key % 2 !== 0 && !is_array($params[$key - 1])) {
                    $this->d .= ", $value";
                } else {
                    $this->d .= " $value";
                }
            }
        }
    }

    /**
     * Draw a line from the current point to the given (x,y) coordinate which becomes the new current point. L
     * (uppercase) indicates that absolute coordinates will follow; l (lowercase) indicates that relative coordinates
     * will follow. A number of coordinates pairs may be specified to draw a polyline. At the end of the command, the
     * new current point is set to the final set of coordinates provided.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataLinetoCommands
     *
     * @param float $x The absolute (relative) X coordinate for the end point of this path segment.
     * @param float $y The absolute (relative) Y coordinate for the end point of this path segment.
     * @param bool  $absolute
     *
     * @return $this
     */
    public function lineTo($x, $y, $absolute = true)
    {
        $this->buildPath($absolute ? 'L' : 'l', $x, $y);

        return $this;
    }

    /**
     * Draws a horizontal line from the current point (cpx, cpy) to (x, cpy). H (uppercase) indicates that absolute
     * coordinates will follow; h (lowercase) indicates that relative coordinates will follow. Multiple x values can be
     * provided (although usually this doesn't make sense). At the end of the command, the new current point becomes
     * (x, cpy) for the final value of x.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataLinetoCommands
     *
     * @param float $x The absolute (relative) X coordinate for the end point of this path segment.
     *
     * @param bool  $absolute
     *
     * @return $this
     */
    public function hLineTo($x, $absolute = true)
    {
        $this->buildPath($absolute ? 'H' : 'h', $x);

        return $this;
    }

    /**
     * Draws a vertical line from the current point (cpx, cpy) to (cpx, y). V (uppercase) indicates that absolute
     * coordinates will follow; v (lowercase) indicates that relative coordinates will follow. Multiple y values can be
     * provided (although usually this doesn't make sense). At the end of the command, the new current point becomes
     * (cpx, y) for the final value of y.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataLinetoCommands
     *
     * @param float $y The absolute (relative) Y coordinate for the end point of this path segment.
     *
     * @param bool  $absolute
     *
     * @return $this
     */
    public function vLineTo($y, $absolute = true)
    {
        $this->buildPath($absolute ? 'V' : 'v', $y);

        return $this;
    }

    /**
     * Draws a cubic Bézier curve from the current point to (x,y) using (x1,y1) as the control point at the beginning
     * of the curve and (x2,y2) as the control point at the end of the curve.
     *
     * @link https://www.w3.org/TR/SVG11/paths.html#PathDataCurveCommands
     *
     * @param float $x1 The absolute (relative) X coordinate for the first control point.
     * @param float $y1 The absolute (relative) Y coordinate for the first control point.
     * @param float $x2 The absolute (relative) X coordinate for the second control point.
     * @param float $y2 The absolute (relative) Y coordinate for the second control point.
     * @param float $x  The absolute (relative) X coordinate for the end point of this path segment.
     * @param float $y  The absolute (relative) Y coordinate for the end point of this path segment.
     *
     * @param bool  $absolute
     *
     * @return $this
     */
    public function curveTo($x1, $y1, $x2, $y2, $x, $y, $absolute = true)
    {
        $this->buildPath($absolute ? 'C' : 'c', $x1, $y1, $x2, $y2, $x, $y);

        return $this;
    }

    /**
     * Draws a cubic Bézier curve from the current point to (x,y). The first control point is assumed to be the
     * reflection of the second control point on the previous command relative to the current point. (If there is no
     * previous command or if the previous command was not an C, c, S or s, assume the first control point is
     * coincident with the current point.) (x2,y2) is the second control point (i.e., the control point at the end of
     * the curve). S (uppercase) indicates that absolute coordinates will follow; s (lowercase) indicates that relative
     * coordinates will follow. Multiple sets of coordinates may be specified to draw a polybézier. At the end of the
     * command, the new current point becomes the final (x,y) coordinate pair used in the polybézier.
     *
     * @link https://www.w3.org/TR/SVG11/paths.html#PathDataCurveCommands
     *
     * @param float $x2 The absolute (relative) X coordinate for the second control point.
     * @param float $y2 The absolute (relative) Y coordinate for the second control point.
     * @param float $x  The absolute (relative) X coordinate for the end point of this path segment.
     * @param float $y  The absolute (relative) Y coordinate for the end point of this path segment.
     *
     * @param bool  $absolute
     *
     * @return $this
     */
    public function smoothCurveTo($x2, $y2, $x, $y, $absolute = true)
    {
        $this->buildPath($absolute ? 'S' : 's', $x2, $y2, $x, $y);

        return $this;
    }

    /**
     * Draws a quadratic Bézier curve from the current point to (x,y) using (x1,y1) as the control point. Q (uppercase)
     * indicates that absolute coordinates will follow; q (lowercase) indicates that relative coordinates will follow.
     * Multiple sets of coordinates may be specified to draw a polybézier. At the end of the command, the new current
     * point becomes the final (x,y) coordinate pair used in the polybézier.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataQuadraticBezierCommands
     *
     * @param float $x1 The absolute (relative) X coordinate for the first control point.
     * @param float $y1 The absolute (relative) Y coordinate for the first control point.
     * @param float $x  The absolute (relative) X coordinate for the end point of this path segment.
     * @param float $y  The absolute (relative) Y coordinate for the end point of this path segment.
     *
     * @param bool  $absolute
     *
     * @return $this
     */
    public function quadraticCurveTo($x1, $y1, $x, $y, $absolute = true)
    {
        $this->buildPath($absolute ? 'Q' : 'q', $x1, $y1, $x, $y);

        return $this;
    }

    /**
     * Draws a quadratic Bézier curve from the current point to (x,y). The control point is assumed to be the
     * reflection of the control point on the previous command relative to the current point. (If there is no previous
     * command or if the previous command was not a Q, q, T or t, assume the control point is coincident with the
     * current point.) T (uppercase) indicates that absolute coordinates will follow; t (lowercase) indicates that
     * relative coordinates will follow. At the end of the command, the new current point becomes the final (x,y)
     * coordinate pair used in the polybézier.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataQuadraticBezierCommands
     *
     * @param float $x The absolute (relative) X coordinate for the end point of this path segment.
     * @param float $y The absolute (relative) Y coordinate for the end point of this path segment.
     *
     * @param bool  $absolute
     *
     * @return $this
     */
    public function smoothQuadraticCurveTo($x, $y, $absolute = true)
    {
        $this->buildPath($absolute ? 'T' : 't', $x, $y);

        return $this;
    }

    /**
     * Draws an elliptical arc from the current point to (x, y). The size and orientation of the ellipse are defined by
     * two radii (rx, ry) and an x-axis-rotation, which indicates how the ellipse as a whole is rotated relative to the
     * current coordinate system. The center (cx, cy) of the ellipse is calculated automatically to satisfy the
     * constraints imposed by the other parameters. large-arc-flag and sweep-flag contribute to the automatic
     * calculations and help determine how the arc is drawn.
     *
     * @link https://www.w3.org/TR/SVG11/paths.html#PathDataEllipticalArcCommands
     *
     * @param float   $rx           The x-axis radius for the ellipse (i.e., r1).
     * @param float   $ry           The y-axis radius for the ellipse
     * @param float   $xRotation    The rotation angle in degrees for the ellipse's x-axis relative to the x-axis of
     *                              the user coordinate system.
     * @param boolean $largeArcFlag The value of the large-arc-flag parameter.
     * @param boolean $sweepFlag    The value of the sweep-flag parameter.
     * @param float   $x            The absolute (relative) X coordinate for the end point of this path segment.
     * @param float   $y            The absolute (relative) Y coordinate for the end point of this path segment.
     * @param bool    $absolute
     *
     * @return $this
     */
    public function arcTo($rx, $ry, $xRotation, $largeArcFlag, $sweepFlag, $x, $y, $absolute = true)
    {
        $this->buildPath($absolute ? 'A' : 'a', [$rx, $ry], $xRotation, [$largeArcFlag ? 1 : 0, $sweepFlag ? 1 : 0], [$x, $y]);

        return $this;
    }

    /**
     * Close the current subpath by drawing a straight line from the current point to current subpath's initial point.
     * Since the Z and z commands take no parameters, they have an identical effect.
     *
     * @link https://www.w3.org/TR/SVG/paths.html#PathDataClosePathCommand
     *
     * @param bool $absolute
     */
    public function closePath($absolute = true)
    {
        $this->buildPath($absolute ? 'Z' : 'z');
    }

    public function getName()
    {
        return 'path';
    }

    public function getBoundingBox()
    {
        return $this->boundingBox->getBox();
    }

    protected function getCenterX()
    {
        // TODO: Implement getCenterX() method.
    }

    protected function getCenterY()
    {
        // TODO: Implement getCenterY() method.
    }

    /**
     * @param $value
     */
    private function addArrayToPath(array $value)
    {
        foreach ($value as $item) {
            $this->d .= " $item,";
        }
        $this->d = rtrim($this->d, ',');
    }

    /**
     *
     */
    private function checkFirstModifier()
    {
        if ($this->d !== null) {
            throw new \BadMethodCallException("First modifier for path must be: M or m. Youb");
        }
    }
}