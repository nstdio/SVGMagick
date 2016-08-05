<?php
namespace nstdio\svg\light;

/**
 * Class DistantLight
 * The following sections define the elements that define a light source, ‘feDistantLight’, ‘fePointLight’ and
 * ‘feSpotLight’, and property ‘lighting-color’, which defines the color of the light.
 *
 * @link     https://www.w3.org/TR/SVG11/filters.html#feDistantLightElement
 *
 * @property float azimuth   = "<number>" Direction angle for the light source on the XY plane (clockwise), in degrees
 *           from the x axis. If the attribute is not specified, then the effect is as if a value of 0 were specified.
 * @property float elevation = "<number>" Direction angle for the light source from the XY plane towards the z axis, in
 *           degrees. Note the positive Z-axis points towards the viewer of the content. If the attribute is not
 *           specified, then the effect is as if a value of 0 were specified.
 * @package  nstdio\svg\light
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class DistantLight extends BaseLight
{

    public function getName()
    {
        return 'feDistantLight';
    }
}