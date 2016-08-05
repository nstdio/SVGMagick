<?php
namespace nstdio\svg\filter;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\util\KeyValueWriter;
use Symfony\Component\Yaml\Exception\RuntimeException;

/**
 * Class ColorMatrix
 * This filter applies a matrix transformation:
 * | R' |     | a00 a01 a02 a03 a04 |   | R |
 * | G' |     | a10 a11 a12 a13 a14 |   | G |
 * | B' |  =  | a20 a21 a22 a23 a24 | * | B |
 * | A' |     | a30 a31 a32 a33 a34 |   | A |
 * | 1  |     |  0   0   0   0   1  |   | 1 |
 * on the RGBA color and alpha values of every pixel on the input graphics to produce a result with a new set of RGBA
 * color and alpha values.
 * The calculations are performed on non-premultiplied color values. If the input graphics consists of premultiplied
 * color values, those values are automatically converted into non-premultiplied color values for this operation.
 * These matrices often perform an identity mapping in the alpha channel. If that is the case, an implementation can
 * avoid the costly undoing and redoing of the premultiplication for all pixels with A = 1.
 *
 * @property string type   = "matrix | saturate | hueRotate | luminanceToAlpha"
 *          Indicates the type of matrix operation. The keyword 'matrix' indicates that a full 5x4 matrix of values
 *          will be provided. The other keywords represent convenience shortcuts to allow commonly used color
 *          operations to be performed without specifying a complete matrix. If attribute 'type' is not specified, then
 *          the effect is as if a value of matrix were specified.
 * @property string values = "list of <number>s". The contents of 'values' depends on the value of attribute
 *           'type'.
 * @link    https://www.w3.org/TR/SVG11/filters.html#feColorMatrixElement
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class ColorMatrix extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feColorMatrix";
    }

    public static function saturate(ContainerInterface $container, $saturation, $filterId = null)
    {
        if ($saturation > 1) {
            $saturation /= 100;
        }

        return self::defaultFilterWithChild($container, [
            'id'     => $filterId,
            'type'   => 'saturate',
            'in'     => 'SourceGraphic',
            'values' => $saturation,
        ]);
    }

    public static function hueRotate(ContainerInterface $container, $angle, $filterId = null)
    {
        return self::defaultFilterWithChild($container, [
            'id'     => $filterId,
            'type'   => 'hueRotate',
            'in'     => 'SourceGraphic',
            'values' => $angle,
        ]);
    }

    public static function luminanceToAlpha(ContainerInterface $container, $filterId = null)
    {
        return self::defaultFilterWithChild($container, [
            'id'     => $filterId,
            'type'   => 'luminanceToAlpha',
            'in'     => 'SourceGraphic',
            'result' => 'a',
        ]);
    }

    public static function luminanceToAlphaWithComposite(ContainerInterface $container, $filterId = null)
    {
        $filter = self::defaultFilterWithChild($container, [
            'id'     => $filterId,
            'type'   => 'luminanceToAlpha',
            'in'     => 'SourceGraphic',
            'result' => 'a',
        ]);

        $composite = new Composite($container);
        KeyValueWriter::apply($composite->getElement(), ['in' => 'SourceGraphic', 'in2' => 'a', 'operator' => 'in']);

        $container->append($filter->append($composite));

        return $filter;
    }

    public static function matrix(ContainerInterface $container, array $matrix, $filterId = null)
    {
        throw new RuntimeException('Method not implemented yet.');
    }
}