<?php
namespace nstdio\svg\light;

/**
 * Class SpotLight
 *
 * @link     https://www.w3.org/TR/SVG11/filters.html#fefeSpotLightElement
 *
 * @property float x                 = "<number>" X location for the light source in the coordinate system established
 *           by attribute ‘primitiveUnits’ on the ‘filter’ element. If the attribute is not specified, then the effect
 *           is as if a value of 0 were specified.
 * @property float y                 = "<number>" Y location for the light source in the coordinate system established
 *           by attribute ‘primitiveUnits’ on the ‘filter’ element. If the attribute is not specified, then the effect
 *           is as if a value of 0 were specified.
 * @property float z                 = "<number>" Z location for the light source in the coordinate system established
 *           by attribute ‘primitiveUnits’ on the ‘filter’ element, assuming that, in the initial coordinate system,
 *           the positive Z-axis comes out towards the person viewing the content and assuming that one unit along the
 *           Z-axis equals one unit in X and Y. If the attribute is not specified, then the effect is as if a value of
 *           0 were specified.
 * @property float pointsAtX         = "<number>" X location in the coordinate system established by attribute
 *           ‘primitiveUnits’ on the ‘filter’ element of the point at which the light source is pointing. If the
 *           attribute is not specified, then the effect is as if a value of 0 were specified.
 * @property float pointsAtY         = "<number>" Y location in the coordinate system established by attribute
 *           ‘primitiveUnits’ on the ‘filter’ element of the point at which the light source is pointing. If the
 *           attribute is not specified, then the effect is as if a value of 0 were specified.
 * @property float pointsAtZ         = "<number>" Z location in the coordinate system established by attribute
 *           ‘primitiveUnits’ on the ‘filter’ element of the point at which the light source is pointing, assuming
 *           that, in the initial coordinate system, the positive Z-axis comes out towards the person viewing the
 *           content and assuming that one unit along the Z-axis equals one unit in X and Y. If the attribute is not
 *           specified, then the effect is as if a value of 0 were specified.
 * @property float specularExponent  = "<number>" Exponent value controlling the focus for the light source. If the
 *           attribute is not specified, then the effect is as if a value of 1 were specified.
 * @property float limitingConeAngle = "<number>" A limiting cone which restricts the region where the light is
 *           projected. No light is projected outside the cone. ‘limitingConeAngle’ represents the angle in degrees
 *           between the spot light axis (i.e. the axis between the light source and the point to which it is pointing
 *           at) and the spot light cone. User agents should apply a smoothing technique such as anti-aliasing at the
 *           boundary of the cone. If no value is specified, then no limiting cone will be applied.
 * @package  nstdio\svg\light
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class SpotLight extends BaseLight
{

    public function getName()
    {
        return 'feSpotLight';
    }
}