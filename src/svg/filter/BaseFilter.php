<?php
namespace nstdio\svg\filter;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\SVGElement;
use nstdio\svg\util\KeyValueWriter;

/**
 * Class BaseFilter
 *
 * @property float  x            = "<coordinate>" The minimum x coordinate for the subregion which restricts
 *           calculation and rendering of the given filter primitive.
 * @property float  y            = "<coordinate>" The minimum y coordinate for the subregion which restricts
 *           calculation and rendering of the given filter primitive.
 * @property float  width        = "<length>" The width of the subregion which restricts calculation and
 *           rendering of the given filter primitive. See filter primitive subregion. A negative value is an error. A
 *           value of zero disables the effect of the given filter primitive (i.e., the result is a transparent black
 *           image).
 * @property float  height       = "<length>" The height of the subregion which restricts calculation and rendering of
 *           the given filter primitive. See filter primitive subregion. A negative value is an error (see Error
 *           processing). A value of zero disables the effect of the given filter primitive (i.e., the result is a
 *           transparent black image).
 * @property string result       = "<filter-primitive-reference>" Assigned name for this filter primitive. If supplied,
 *           then graphics that result from processing this filter primitive can be referenced by an 'in' attribute on
 *           a subsequent filter primitive within the same 'filter' element. If no value is provided, the output will
 *           only be available for re-use as the implicit input into the next filter primitive if that filter primitive
 *           provides no value for its 'in' attribute. Note that a <filter-primitive-reference> is not an XML ID;
 *           instead, a <filter-primitive-reference> is only meaningful within a given 'filter' element and thus have
 *           only local scope. It is legal for the same <filter-primitive-reference> to appear multiple times within
 *           the same 'filter' element. When referenced, the <filter-primitive-reference> will use the closest
 *           preceding filter primitive with the given result.
 * @property string in           = "SourceGraphic | SourceAlpha | BackgroundImage | BackgroundAlpha | FillPaint |
 *           StrokePaint | <filter-primitive-reference>" Identifies input for the given filter primitive. The value can
 *           be either one of six keywords or can be a string which matches a previous 'result' attribute value within
 *           the same 'filter' element. If no value is provided and this is the first filter primitive, then this
 *           filter primitive will use SourceGraphic as its input. If no value is provided and this is a subsequent
 *           filter primitive, then this filter primitive will use the result from the previous filter primitive as its
 *           input. {@link https://www.w3.org/TR/SVG11/filters.html#FilterPrimitiveInAttribute}
 * @package  nstdio\svg\filter
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
abstract class BaseFilter extends SVGElement
{

    protected static function defaultFilterWithChild(ContainerInterface $container, array $options)
    {
        $filter = self::defaultFilter($container, $options['id']);
        unset($options['id']);
        $child = new static($container);
        KeyValueWriter::apply($child->getElement(), $options);

        $container->append($filter->append($child));

        return $filter;
    }

    /**
     * @param ContainerInterface  $container
     * @param                     $filterId
     *
     * @return Filter
     */
    protected static function defaultFilter(ContainerInterface $container, $filterId)
    {
        return self::filterWithOptions($container, [
            'id'          => $filterId,
            'filterUnits' => 'objectBoundingBox',
            'x'           => '0%',
            'y'           => '0%',
            'width'       => '100%',
            'height'      => '100%',
        ]);
    }

    protected static function filterWithOptions(ContainerInterface $svg, array $options)
    {
        $filter = new Filter($svg, $options['id']);
        unset($options['id']);
        KeyValueWriter::apply($filter->getElement(), $options);

        return $filter;
    }
}