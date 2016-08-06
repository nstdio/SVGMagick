<?php
namespace nstdio\svg\animation;

use nstdio\svg\attributes\AnimationAddition;
use nstdio\svg\attributes\AnimationEvent;
use nstdio\svg\attributes\AnimationTiming;
use nstdio\svg\attributes\AnimationValue;
use nstdio\svg\attributes\ConditionalProcessing;
use nstdio\svg\attributes\ExternalResourcesRequired;
use nstdio\svg\attributes\XLink;
use nstdio\svg\ElementInterface;

/**
 * Class AnimateMotion
 * The animateMotion element causes a referenced element to move along a motion path.
 *
 * @link    https://www.w3.org/TR/SVG11/animate.html#AnimateMotionElement
 * @property string calcMode  = discrete | linear | paced | spline. Specifies the interpolation mode for the animation.
 *           Refer to general description of the
 *           'calcMode' attribute above. The only difference is that the default value for the 'calcMode' for
 *           'animateMotion' is 'paced'.
 * @property string path      The motion path, expressed in the same format and interpreted the same way as the 'd'
 *           attribute on the 'path' element. The effect of a motion path animation is to add a supplemental
 *           transformation matrix onto the CTM for the referenced object which causes a translation along the x- and
 *           y-axes of the current user coordinate system by the computed X and Y values computed over time.
 * @property string keyPoints 'keyPoints' takes a semicolon-separated list of floating point values between 0 and 1 and
 *           indicates how far along the motion path the object shall move at the moment in time specified by
 *           corresponding 'keyTimes' value. Distance calculations use the user agent's distance along the path
 *           algorithm. Each progress value in the list corresponds to a value in the 'keyTimes' attribute list.
 * @property string rotate    = "<number> | auto | auto-reverse". The 'rotate' attribute post-multiplies a supplemental
 *           transformation matrix onto the CTM of the target element to apply a rotation transformation about the
 *           origin of the current user coordinate system. The rotation transformation is applied after the
 *           supplemental translation transformation that is computed due to the 'path' attribute.
 * @property string origin    "default" The 'origin' attribute has no effect in SVG.
 *
 * @package nstdio\svg\animation
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class AnimateMotion extends BaseAnimation implements ConditionalProcessing, AnimationEvent, AnimationValue, AnimationAddition, AnimationTiming, XLink, ExternalResourcesRequired
{
    /**
     * AnimateMotion constructor.
     *
     * @param ElementInterface $parent
     * @param MPath            $mpath
     */
    public function __construct(ElementInterface $parent, MPath $mpath)
    {
        parent::__construct($parent);

        $this->getElement()->appendChild($mpath->getElement());
        $this->calcMode = 'paced';
        $this->rotate = 0;
    }

    public function getName()
    {
        return 'animateMotion';
    }
}