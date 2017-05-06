<?php
namespace nstdio\svg\container;

use nstdio\svg\attributes\Transformable;
use nstdio\svg\ElementInterface;
use nstdio\svg\shape\Line;
use nstdio\svg\shape\Shape;
use nstdio\svg\traits\TransformTrait;
use nstdio\svg\util\transform\Transform;
use nstdio\svg\util\transform\TransformInterface;

/**
 * Class Pattern
 *
 * A pattern is used to fill or stroke an object using a pre-defined graphic object which can be replicated ("tiled")
 * at fixed intervals in x and y to cover the areas to be painted. Patterns are defined using a 'pattern' element and
 * then referenced by properties 'fill' and 'stroke' on a given graphics element to indicate that the given element
 * shall be filled or stroked with the referenced pattern.
 *
 * @link    https://www.w3.org/TR/SVG/pservers.html#Patterns
 * @property string $patternUnits        "userSpaceOnUse | objectBoundingBox" Defines the coordinate system for
 *           attributes 'x',
 *           'y', 'width' and 'height'. If patternUnits="userSpaceOnUse", 'x', 'y', 'width' and 'height' represent
 *           values in the coordinate system that results from taking the current user coordinate system in place at
 *           the time when the 'pattern' element is referenced (i.e., the user coordinate system for the element
 *           referencing the 'pattern' element via a 'fill' or 'stroke' property) and then applying the transform
 *           specified by attribute 'patternTransform'. If patternUnits="objectBoundingBox", the user coordinate system
 *           for attributes 'x', 'y', 'width' and 'height' is established using the bounding box of the element to
 *           which the pattern is applied (see Object bounding box units) and then applying the transform specified by
 *           attribute 'patternTransform'. If attribute 'patternUnits' is not specified, then the effect is as if a
 *           value of 'objectBoundingBox' were specified.
 * @property string $patternContentUnits = "userSpaceOnUse | objectBoundingBox" Defines the coordinate system for the
 *           contents of the ‘pattern’. Note that this attribute has no effect if attribute ‘viewBox’ is specified. If
 *           patternContentUnits="userSpaceOnUse", the user coordinate system for the contents of the ‘pattern’ element
 *           is the coordinate system that results from taking the current user coordinate system in place at the time
 *           when the ‘pattern’ element is referenced (i.e., the user coordinate system for the element referencing the
 *           ‘pattern’ element via a ‘fill’ or ‘stroke’ property) and then applying the transform specified by
 *           attribute ‘patternTransform’. If patternContentUnits="objectBoundingBox", the user coordinate system for
 *           the contents of the ‘pattern’ element is established using the bounding box of the element to which the
 *           pattern is applied (see Object bounding box units) and then applying the transform specified by attribute
 *           ‘patternTransform’. If attribute ‘patternContentUnits’ is not specified, then the effect is as if a value
 *           of 'userSpaceOnUse' were specified.
 * @property string $patternTransform    = "<transform-list>" Contains the definition of an optional additional
 *           transformation from the pattern coordinate system onto the target coordinate system (i.e.,
 *           'userSpaceOnUse' or 'objectBoundingBox'). This allows for things such as skewing the pattern tiles. This
 *           additional transformation matrix is post-multiplied to (i.e., inserted to the right of) any previously
 *           defined transformations, including the implicit transformation necessary to convert from object bounding
 *           box units to user space. If attribute ‘patternTransform’ is not specified, then the effect is as if an
 *           identity transform were specified.
 * @property float  $x                   = "<coordinate>" ‘x’, ‘y’, ‘width’ and ‘height’ indicate how the pattern tiles
 *           are placed and spaced. These attributes represent coordinates and values in the coordinate space specified
 *           by the combination of attributes ‘patternUnits’ and ‘patternTransform’. If the attribute is not specified,
 *           the effect is as if a value of zero were specified.
 * @property float  $y                   = "<coordinate>" See ‘x’. If the attribute is not specified, the effect is as
 *           if a value of zero were specified.
 * @property float  $width               = "<length>" See ‘x’. A negative value is an error (see Error processing). A
 *           value of zero disables rendering of the element (i.e., no paint is applied). If the attribute is not
 *           specified, the effect is as if a value of zero were specified.
 * @property float  $height              = "<length>" See ‘x’. A negative value is an error (see Error processing). A
 *           value of zero disables rendering of the element (i.e., no paint is applied). If the attribute is not
 *           specified, the effect is as if a value of zero were specified.
 * @property float  $xlinkHref           = "<iri>" An IRI reference to a different ‘pattern’ element within the
 *           current SVG document fragment. Any attributes which are defined on the referenced element which are not
 *           defined on this element are inherited by this element. If this element has no children, and the referenced
 *           element does (possibly due to its own ‘xlink:href’ attribute), then this element inherits the children
 *           from the referenced element. Inheritance can be indirect to an arbitrary level; thus, if the referenced
 *           element inherits attributes or children due to its own ‘xlink:href’ attribute, then the current element
 *           can inherit those attributes or children.
 * @package nstdio\svg\container
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Pattern extends Container implements TransformInterface, Transformable
{
    use TransformTrait;

    /**
     * Pattern constructor.
     *
     * @param ElementInterface $parent
     * @param null|string      $id
     */
    public function __construct(ElementInterface $parent, $id = null)
    {
        if ($parent instanceof SVG) {
            $defs = $parent->getFirstChild();
            $parent = $defs;
        }
        parent::__construct($parent);

        $this->transformImpl = Transform::newInstance($this->getTransformAttribute());
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'pattern';
    }

    /**
     * @param ContainerInterface $container
     * @param Shape              $shape
     * @param array              $patternConfig
     * @param null               $id
     *
     * @return $this
     */
    public static function withShape(ContainerInterface $container, Shape $shape, array $patternConfig = [], $id = null)
    {
        $patternConfig = array_merge(self::getDefaultConfig(), $patternConfig);

        $shapeBox = $shape->getBoundingBox();
        $patternConfig['width'] = $shapeBox['width'];
        $patternConfig['height'] = $shapeBox['height'];

        $pattern = (new self($container, $id))->apply($patternConfig);
        $shape->getRoot()->removeChild($shape);
        $pattern->append($shape);

        return $pattern;
    }

    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    public static function verticalHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        return self::hatch($container, $patternConfig, $lineConfig, $id);
    }

    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    public static function horizontalHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        return self::hatch($container, $patternConfig, $lineConfig, $id)->rotate(90);
    }


    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    public static function straightCrossHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        return self::crossHatch($container, $patternConfig, $lineConfig, $id)
            ->clearTransformation()
            ->rotate(90);
    }

    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    public static function straightCrossHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        return self::crossHatch($container, $patternConfig, $lineConfig, $id)
            ->clearTransformation()
            ->rotate(90);
    }

    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    protected static function hatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        $patternConfig = array_merge(self::getDefaultConfig(), $patternConfig);
        $lineDefaultConfig = ['stroke' => 'black', 'stroke-width' => 1, 'fill' => 'none'];
        $lineConfig = array_merge($lineDefaultConfig, $lineConfig);

        $pattern = (new self($container, $id))->apply($patternConfig);

        Line::create($pattern, 0, 0, 0, $pattern->height)->apply($lineConfig);

        return $pattern;
    }

    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    public static function crossHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        if (isset($patternConfig['width'])) {
            $patternConfig['height'] = $patternConfig['width'];
        }
        if (isset($patternConfig['height'])) {
            $patternConfig['width'] = $patternConfig['height'];
        }

        /** @var Pattern $pattern */
        $pattern = self::diagonalHatch($container, $patternConfig, $lineConfig, $id);

        /** @var Line $firstLine */
        $firstLine = $pattern->getFirstChild()->apply([
            'x1' => 0,
            'y1' => $pattern->height / 2,
            'x2' => $pattern->width,
            'y2' => $pattern->height / 2,
        ]);

        $attrs = $firstLine->allAttributes(['x1', 'y1', 'x2', 'y2', 'id']);
        $line = Line::create($pattern, $pattern->width / 2, 0, $pattern->width / 2, $pattern->height)
            ->apply($attrs);
        $line->id = null;

        return $pattern;
    }

    /**
     * @return array
     */
    protected static function getDefaultConfig()
    {
        return ['x' => 0, 'y' => 0, 'height' => 4, 'width' => 4, 'patternUnits' => 'userSpaceOnUse'];
    }

    /**
     * @param ContainerInterface $container
     * @param array              $patternConfig
     * @param array              $lineConfig
     * @param null|string        $id
     *
     * @return $this
     */
    public static function diagonalHatch(ContainerInterface $container, array $patternConfig = [], array $lineConfig = [], $id = null)
    {
        return self::hatch($container, $patternConfig, $lineConfig, $id)->rotate(45);
    }

    /**
     * @inheritdoc
     */
    public function getTransformAttribute()
    {
        return $this->patternTransform;
    }

    /**
     * @inheritdoc
     */
    public function setTransformAttribute($transformList)
    {
        $this->patternTransform = $transformList;

        return $this;
    }
}