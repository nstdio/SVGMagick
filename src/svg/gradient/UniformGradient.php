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

    /**
     * @param ElementInterface $svg
     * @param array            $colors
     * @param null             $id
     * @param string           $gradientType
     *
     * @return UniformGradient
     */
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
        $offsets = array_map(function($item) {
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

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function verticalFromTop(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('topBottom'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function verticalFromBottom(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('bottomTop'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function diagonalFromTopLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('topLeftBottomRight'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function diagonalFromBottomRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('bottomRightTopLeft'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function diagonalFromBottomLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('bottomLeftTopRight'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function diagonalFromTopRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('topRightBottomLeft'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function horizontalFromLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('leftRight'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function horizontalFromRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id)->apply(Direction::get('rightLeft'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialTopLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialTopLeft'));
    }


    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialTopRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialTopRight'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialBottomLeft(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialBottomLeft'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialBottomRight(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialBottomRight'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialTopCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialTopCenter'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialLeftCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialLeftCenter'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialBottomCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialBottomCenter'));
    }

    /**
     * @param ContainerInterface $container
     * @param array              $colors
     * @param null               $id
     *
     * @return $this|ElementInterface
     */
    public static function radialRightCenter(ContainerInterface $container, array $colors, $id = null)
    {
        return self::gradient($container, $colors, $id, self::RADIAL)->apply(Direction::get('radialRightCenter'));
    }

    /**
     * @param ElementInterface $svg
     * @param                  $id
     * @param                  $gradientType
     *
     * @return Gradient
     */
    private static function getGradient(ElementInterface $svg, $id, $gradientType)
    {
        return $gradientType === self::LINEAR ? new LinearGradient($svg, $id) : new RadialGradient($svg, $id);
    }
}