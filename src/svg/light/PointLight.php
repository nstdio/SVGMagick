<?php
namespace nstdio\svg\light;

/**
 * Class PointLight
 *
 * @link     https://www.w3.org/TR/SVG11/filters.html#fePointLightElement
 *
 * @property float x = "<number>" X location for the light source in the coordinate system established by attribute
 *           ‘primitiveUnits’ on the ‘filter’ element. If the attribute is not specified, then the effect is as if a
 *           value of 0 were specified.
 * @property float y = "<number>" Y location for the light source in the coordinate system established by attribute
 *           ‘primitiveUnits’ on the ‘filter’ element. If the attribute is not specified, then the effect is as if a
 *           value of 0 were specified.
 * @property float z = "<number>" Z location for the light source in the coordinate system established by attribute
 *           ‘primitiveUnits’ on the ‘filter’ element, assuming that, in the initial coordinate system, the positive
 *           Z-axis comes out towards the person viewing the content and assuming that one unit along the Z-axis equals
 *           one unit in X and Y. If the attribute is not specified, then the effect is as if a value of 0 were
 *           specified.
 * @package  nstdio\svg\light
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class PointLight extends BaseLight
{

    public function getName()
    {
        return 'fePointLight';
    }
}