<?php
namespace nstdio\svg\gradient;

/**
 * Class Direction
 *
 * @package nstdio\svg\gradient
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Direction
{
    private static $attrs = ['x1', 'y1', 'x2', 'y2'];

    private static $radialAttrs = ['fx', 'fy', 'r'];

    private static $topLeftBottomRight = [0, 0, 100, 100];

    private static $bottomRightTopLeft = [100, 100, 0, 0];

    private static $topBottom = [0, 0, 0, 100];

    private static $bottomTop = [0, 100, 0, 0];

    private static $leftRight = [0, 50, 100, 50];

    private static $rightLeft = [100, 50, 0, 50];

    private static $bottomLeftTopRight = [0, 100, 100, 0];

    private static $topRightBottomLeft = [100, 0, 0, 100];

    private static $radialTopLeft = [0, 0, 100];

    private static $radialTopRight = [100, 0, 100];

    private static $radialBottomRight = [100, 100, 100];

    private static $radialBottomLeft = [0, 100, 100];

    private static $radialTopCenter = [50, 0, 100];

    private static $radialLeftCenter = [0, 50, 100];

    private static $radialBottomCenter = [50, 100, 100];

    private static $radialRightCenter = [100, 50, 100];

    public static function get($staticProp)
    {
        if (isset(self::${$staticProp})) {
            $isRadial = strpos($staticProp, 'radial') === 0;
            return self::buildDirection(self::${$staticProp}, $isRadial);
        }

        return [];
    }

    private static function buildDirection(array $property, $radial = false)
    {
        $direction = [];
        $attributes = $radial ? self::$radialAttrs : self::$attrs;
        foreach ($attributes as $key => $attr) {
            $direction[$attr] = $property[$key] . '%';
        }

        return $direction;
    }
}