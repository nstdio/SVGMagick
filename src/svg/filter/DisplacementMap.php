<?php
namespace nstdio\svg\filter;

/**
 * Class DisplacementMap
 * This filter primitive uses the pixels values from the image from ‘in2’ to spatially displace the image from ‘in’.
 * This is the transformation to be performed:
 *
 * P'(x,y) <- P( x + scale * (XC(x,y) - .5), y + scale * (YC(x,y) - .5))
 *
 * where P(x,y) is the input image, ‘in’, and P'(x,y) is the destination. XC(x,y) and YC(x,y) are the component values
 * of the channel designated by the xChannelSelector and yChannelSelector. For example, to use the R component of ‘in2’
 * to control displacement in x and the G component of Image2 to control displacement in y, set xChannelSelector to "R"
 * and yChannelSelector to "G".
 *
 * @link    https://www.w3.org/TR/SVG11/filters.html#feDisplacementMapElement
 * @property float  scale            = "<number>" Displacement scale factor. The amount is expressed in the coordinate
 *           system established by attribute ‘primitiveUnits’ on the ‘filter’ element. When the value of this attribute
 *           is 0, this operation has no effect on the source image. If the attribute is not specified, then the effect
 *           is as if a value of 0 were specified.
 * @property float  xChannelSelector = "R | G | B | A" Indicates which channel from ‘in2’ to use to displace the pixels
 *           in ‘in’ along the x-axis. If attribute ‘xChannelSelector’ is not specified, then the effect is as if a
 *           value of A were specified.
 * @property float  yChannelSelector = "R | G | B | A" Indicates which channel from ‘in2’ to use to displace the pixels
 *           in ‘in’ along the y-axis. If attribute ‘yChannelSelector’ is not specified, then the effect is as if a
 *           value of A were specified.
 * @property string in2              = "(see ‘in’ attribute)" The second input image, which is used to displace the
 *           pixels in the image from attribute ‘in’. This attribute can take on the same values as the ‘in’ attribute.
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class DisplacementMap extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feDisplacementMap";
    }

}