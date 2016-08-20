<?php
namespace nstdio\svg\gradient;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;

/**
 * Class UniformGradient
 *
 * @package nstdio\svg\gradient
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class UniformGradient extends Gradient
{

    public static function gradient(ElementInterface $svg, array $colors, $id = null, $gradientType = 'linear')
    {
        $gradient = self::getGradient($svg, $id, $gradientType);
        if (count($colors) <= 1) {
            $colors = array_merge($colors, $colors);
            if (empty($colors)) {
                $colors = ['white', 'black'];
            }
        }
        $step = 1 / (count($colors) - 1);
        $offsets = array_map(function ($item) {
            return $item % 1 !== 0 ? sprintf('%0.2f', $item) : $item;
        }, range(0, 1, $step));

        foreach ($colors as $key => $color) {
            $ret = [];
            $ret['offset'] = $offsets[$key];
            $ret['stop-color'] = $color;
            $gradient->appendStop(new Stop($gradient, $ret));
        }

        return $gradient;
    }

    public static function verticalFromTop(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('topBottom'));
    }

    public static function verticalFromBottom(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('bottomTop'));
    }

    public static function diagonalFromTopLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('topLeftBottomRight'));
    }

    public static function diagonalFromBottomRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('bottomRightTopLeft'));
    }

    public static function diagonalFromBottomLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('bottomLeftTopRight'));
    }

    public static function diagonalFromTopRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('topRightBottomLeft'));
    }

    public static function horizontalFromLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('leftRight'));
    }

    public static function horizontalFromRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('rightLeft'));
    }

    public static function radialTopLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialTopLeft'));
    }


    public static function radialTopRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialTopRight'));
    }

    public static function radialBottomLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialBottomLeft'));
    }

    public static function radialBottomRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialBottomRight'));
    }

    public static function radialTopCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialTopCenter'));
    }

    public static function radialLeftCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialLeftCenter'));
    }

    public static function radialBottomCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialBottomCenter'));
    }

    public static function radialRightCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialRightCenter'));
    }

    /**
     * @param ElementInterface $svg
     * @param                  $id
     * @param                  $gradientType
     *
     * @return LinearGradient|RadialGradient
     */
    private static function getGradient(ElementInterface $svg, $id, $gradientType)
    {
        return $gradientType === self::LINEAR ? new LinearGradient($svg, $id) : new RadialGradient($svg, $id);
    }
}