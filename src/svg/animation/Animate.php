<?php
namespace nstdio\svg\animation;

use nstdio\svg\attributes\ConditionalProcessing;
use nstdio\svg\attributes\ExternalResourcesRequired;
use nstdio\svg\attributes\FullyAnimatable;
use nstdio\svg\attributes\XLink;
use nstdio\svg\ElementInterface;
use nstdio\svg\util\KeyValueWriter;

/**
 * Class Animate
 * The animate element is put inside a shape element and defines how an attribute of an element changes over the
 * animation. The attribute will change from the initial value to the end value in the duration specified.
 *
 * @link     https://developer.mozilla.org/en-US/docs/Web/SVG/Element/animate
 * @property int|string repeatCount   his attribute indicates the number of time the animation will take place. This
 *           attribute's value specifies the number of iterations. It can include partial iterations expressed as
 *           fraction values. Its value must be greater than 0. Value must be <number> or "indefinite".
 *
 * @package  nstdio\svg\animation
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class Animate extends BaseAnimation implements FullyAnimatable, XLink, ExternalResourcesRequired, ConditionalProcessing
{
    /**
     * Animate constructor.
     *
     * @param ElementInterface $parent
     * @param string           $attributeName
     * @param float            $from
     * @param float            $to
     * @param string           $dur
     * @param string           $attributeType
     * @param int|string       $repeatCount
     */
    public function __construct(ElementInterface $parent, $attributeName, $from, $to, $dur, $attributeType = 'auto', $repeatCount = null)
    {
        parent::__construct($parent);

        KeyValueWriter::apply($this->element, [
            'attributeType' => $attributeType,
            'attributeName' => $attributeName,
            'from' => $from,
            'to' => $to,
            'dur' => $dur,
            'repeatCount' => $repeatCount,
        ]);
    }

    public function getName()
    {
        return 'animate';
    }
}