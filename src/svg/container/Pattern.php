<?php
namespace nstdio\svg\container;

use nstdio\svg\ElementInterface;
use nstdio\svg\shape\Line;
use nstdio\svg\shape\Shape;

/**
 * Class Pattern
 *
 * @package nstdio\svg\container
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Pattern extends Container
{
    public function __construct(ElementInterface $parent, $id = null)
    {
        if ($parent instanceof SVG) {
            $defs = $parent->getFirstChild();
            $parent = $defs;
        }
        parent::__construct($parent);

        $this->id = $id;
    }

    public function getName()
    {
        return 'pattern';
    }

    public static function withShape(ContainerInterface $container, Shape $shape, array $patternConfig = [], $id = null)
    {
        $patternConfig = array_merge(self::getDefaultConfig(), $patternConfig);

        $shapeBox = $shape->getBoundingBox();
        $patternConfig['width'] = $shapeBox['width'];
        $patternConfig['height'] = $shapeBox['height'];

        $pattern = (new self($container, $id))->apply($patternConfig);
        $shape->selfRemove();
        $pattern->append($shape);

        return $pattern;
    }

    public static function verticalHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        return self::hatch($container, $patternConfig, $lineConfig, $id);
    }

    public static function horizontalHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        $patternConfig['patternTransform'] = "rotate(90)";

        return self::hatch($container, $patternConfig, $lineConfig, $id);
    }

    public static function diagonalHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        $patternConfig['patternTransform'] = "rotate(45)";

        return self::hatch($container, $patternConfig, $lineConfig, $id);
    }

    public static function crossHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        if (isset($patternConfig['width'])) {
            $patternConfig['height'] = $patternConfig['width'];
        }
        if (isset($patternConfig['height'])) {
            $patternConfig['width'] = $patternConfig['height'];
        }

        $pattern = self::hatch($container, $patternConfig, $lineConfig, $id);

        /** @var Line $firstLine */
        $firstLine = $pattern->getFirstChild()->apply(['x1' => 0, 'y1' => 0, 'x2' => $pattern->height, 'y2' => $pattern->width]);
        $attrs = $firstLine->allAttributes(['x1', 'y1', 'x2', 'y2', 'id']);
        $line = new Line($pattern, 0, $pattern->width, $pattern->height, 0);
        $line->id = null;
        $line->apply($attrs);

        return $pattern;
    }

    public static function straightCrossHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        $patternConfig['patternTransform'] = 'rotate(45)';

        return self::crossHatch($container, $patternConfig, $lineConfig, $id);
    }

    protected static function hatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        $patternConfig = array_merge(self::getDefaultConfig(), $patternConfig);
        $lineDefaultConfig = ['stroke' => 'black', 'stroke-width' => 1, 'fill' => 'none'];
        $lineConfig = array_merge($lineDefaultConfig, $lineConfig);

        $pattern = (new self($container, $id))->apply($patternConfig);

        (new Line($pattern, 0, 0, 0, $pattern->height))->apply($lineConfig);

        return $pattern;
    }

    protected static function getDefaultConfig()
    {
        return ['x' => 0, 'y' => 0, 'height' => 4, 'width' => 4, 'patternUnits' => 'userSpaceOnUse'];
    }
}