<?php
namespace nstdio\svg\animation;

use nstdio\svg\attributes\AnimationEvent;
use nstdio\svg\attributes\AnimationTarget;
use nstdio\svg\attributes\AnimationTiming;
use nstdio\svg\attributes\ConditionalProcessing;
use nstdio\svg\attributes\ExternalResourcesRequired;
use nstdio\svg\attributes\XLink;

/**
 * Class Set
 * The 'set' element provides a simple means of just setting the value of an attribute for a specified duration. It
 * supports all attribute types, including those that cannot reasonably be interpolated, such as string and boolean
 * values. The 'set' element is non-additive. The additive and accumulate attributes are not allowed, and will be
 * ignored if specified.
 *
 * @link    https://www.w3.org/TR/SVG/animate.html#SetElement
 * @package nstdio\svg\animation
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Set extends BaseAnimation implements AnimationEvent, AnimationTarget, AnimationTiming, XLink, ConditionalProcessing, ExternalResourcesRequired
{

    public function getName()
    {
        return 'set';
    }
}