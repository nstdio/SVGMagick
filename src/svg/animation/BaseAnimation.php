<?php
namespace nstdio\svg\animation;

use nstdio\svg\attributes\AnimationAddition;
use nstdio\svg\attributes\AnimationValue;
use nstdio\svg\SVGElement;

/**
 * Class BaseAnimation
 *
 * @property string attributeType This attribute specifies the namespace in which the target attribute and its
 *           associated values are defined. The attribute value is one of the following (values are case-sensitive):
 *           CSS | XML | auto. The default value is 'auto'.
 *
 * @property string attributeName This attribute indicates the name of the attribute in the parent element that is
 *           going to be changed during an animation.
 * @property float  from This attribute indicates the initial value of the attribute that will be modified
 *           during the animation. When used with the to attribute, the animation will change the modified attribute
 *           from the from value to the to value.
 * @property float  to            This attribute indicates the final value of the attribute that will be modified
 *           during the animation. The value of the attribute will change between the from attribute value and this
 *           value. By default the change will be linear.
 * @property string dur           This attribute indicates the simple duration of the animation. <clock-value> |
 *           indefinite. <clock-value> Specifies the length of the simple duration. Value must be greater than 0. This
 *           value can be express within hours (h), minutes (m), seconds (s) or milliseconds (ms). It's possible to
 *           combine those time representation to provide some complex durations like this: hh:mm:ss.iii or like this:
 *           mm:ss.iii
 * @package  nstdio\svg\animation
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
abstract class BaseAnimation extends SVGElement implements AnimationAddition, AnimationValue
{

}