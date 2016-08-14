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

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $topLeftBottomRight = [0, 0, 100, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $bottomRightTopLeft = [100, 100, 0, 0];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $topBottom = [0, 0, 0, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $bottomTop = [0, 100, 0, 0];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $leftRight = [0, 50, 100, 50];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $rightLeft = [100, 50, 0, 50];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $bottomLeftTopRight = [0, 100, 100, 0];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $topRightBottomLeft = [100, 0, 0, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialTopLeft = [0, 0, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialTopRight = [100, 0, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialBottomRight = [100, 100, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialBottomLeft = [0, 100, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialTopCenter = [50, 0, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialLeftCenter = [0, 50, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialBottomCenter = [50, 100, 100];

    private static /** @noinspection PhpUnusedPrivateFieldInspection */
        $radialRightCenter = [100, 50, 100];

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