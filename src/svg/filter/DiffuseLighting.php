<?php
namespace nstdio\svg\filter;
use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;
use nstdio\svg\light\PointLight;

/**
 * Class DiffuseLighting
 * This filter primitive lights an image using the alpha channel as a bump map. The resulting image is an RGBA opaque
 * image based on the light color with alpha = 1.0 everywhere. The lighting calculation follows the standard diffuse
 * component of the Phong lighting model. The resulting image depends on the light color, light position and surface
 * geometry of the input bump map.
 *
 * The light map produced by this filter primitive can be combined with a texture image using the multiply term of the
 * arithmetic ‘feComposite’ compositing method. Multiple light sources can be simulated by adding several of these
 * light maps together before applying it to the texture image.
 *
 * The light source is defined by one of the child elements ‘feDistantLight’, ‘fePointLight’ or ‘feSpotLight’. The
 * light color is specified by property ‘lighting-color’.
 *
 * @link     https://www.w3.org/TR/SVG11/filters.html#feDiffuseLightingElement
 * @property float surfaceScale     = "<number>" height of surface when Ain = 1. If the attribute is not specified,
 *           then
 *           the effect is as if a value of 1 were specified.
 * @property float diffuseConstant  = "<number>" kd in Phong lighting model. In SVG, this can be any non-negative
 *           number. If the attribute is not specified, then the effect is as if a value of 1 were specified.
 * @property float kernelUnitLength = "<number-optional-number>" The first number is the <dx> value. The second number
 *           is the <dy> value. If the <dy> value is not specified, it defaults to the same value as <dx>. Indicates
 *           the intended distance in current filter units (i.e., units as determined by the value of attribute
 *           ‘primitiveUnits’) for dx and dy, respectively, in the surface normal calculation formulas. By specifying
 *           value(s) for ‘kernelUnitLength’, the kernel becomes defined in a scalable, abstract coordinate system. If
 *           ‘kernelUnitLength’ is not specified, the dx and dy values should represent very small deltas relative to a
 *           given (x,y) position, which might be implemented in some cases as one pixel in the intermediate image
 *           offscreen bitmap, which is a pixel-based coordinate system, and thus potentially not scalable. For some
 *           level of consistency across display media and user agents, it is necessary that a value be provided for at
 *           least one of ‘filterRes’ and ‘kernelUnitLength’. Discussion of intermediate images are in the Introduction
 *           and in the description of attribute ‘filterRes’.
 * @package  nstdio\svg\filter
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class DiffuseLighting extends BaseFilter
{
    public function __construct(ElementInterface $parent)
    {
        parent::__construct($parent);
        $this->apply([
            'in' => 'SourceGraphic',
            'result' => 'light',
            'lighting-color' => 'white'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feDiffuseLighting";
    }

    public static function diffusePointLight(ContainerInterface $container, array $pointLightConfig, array $diffuseLightingConfig = [], $filterId = null)
    {
        $filter = self::defaultFilter($container, $filterId);

        $diffLight = (new DiffuseLighting($filter))->apply($diffuseLightingConfig);
        (new PointLight($diffLight))->apply($pointLightConfig);
        (new Composite($filter))->apply(['in2' => $diffLight->result, 'operator' => 'arithmetic', 'k1' => 1]);

        return $filter;
    }
}