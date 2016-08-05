<?php
namespace nstdio\svg\animation;

use nstdio\svg\attributes\ConditionalProcessing;
use nstdio\svg\attributes\ExternalResourcesRequired;
use nstdio\svg\attributes\FullyAnimatable;
use nstdio\svg\attributes\XLink;

/**
 * Class AnimateTransform
 * The 'animateTransform' element animates a transformation attribute on a target element, thereby allowing animations
 * to control translation, scaling, rotation and/or skewing.
 *
 * @link    https://www.w3.org/TR/SVG/animate.html#AnimateTransformElement
 *
 * @property string type = "translate | scale | rotate | skewX | skewY". Indicates the type of transformation which is
 *           to have its values change over time. If the attribute is not specified, then the effect is as if a value
 *           of 'translate' were specified.
 *
 * @package nstdio\svg\animation
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class AnimateTransform extends BaseAnimation implements FullyAnimatable, ConditionalProcessing, XLink, ExternalResourcesRequired
{
    public function getName()
    {
        return 'animateTransform';
    }
}