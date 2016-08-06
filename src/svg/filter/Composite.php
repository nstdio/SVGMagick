<?php
namespace nstdio\svg\filter;
use nstdio\svg\ElementInterface;

/**
 * Class Composite
 * This filter performs the combination of the two input images pixel-wise in image space using one of the Porter-Duff
 * compositing operations: over, in, atop, out, xor. Additionally, a component-wise
 * arithmetic operation (with the result clamped between [0..1]) can be applied.
 *
 * @link    https://www.w3.org/TR/SVG11/filters.html#feCompositeElement
 *
 * @property string operator = "over | in | out | atop | xor | arithmetic" The compositing operation that is to be
 *           performed. All of the 'operator' types except arithmetic match the corresponding operation as described in
 *           [PORTERDUFF]. The arithmetic operator is described above. If attribute 'operator' is not specified, then
 *           the effect is as if a value of over were specified.
 * @property float  k1       = "<number>" Only applicable if operator="arithmetic". If the attribute is not
 *           specified, the effect is as if a value of 0 were specified.
 * @property float  k2       = "<number>" Only applicable if operator="arithmetic". If the attribute is not
 *           specified, the effect is as if a value of 0 were specified.
 * @property float  k3       = "<number>" Only applicable if operator="arithmetic". If the attribute is not
 *           specified, the effect is as if a value of 0 were specified.
 * @property float  k4       = "<number>" Only applicable if operator="arithmetic". If the attribute is not
 *           specified, the effect is as if a value of 0 were specified.
 * @property string in2      = "(see 'in' attribute)" The second input image to the compositing operation. This
 *           attribute can take on the same values as the 'in' attribute.
 *
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Composite extends BaseFilter
{
    public function __construct(ElementInterface $parent)
    {
        parent::__construct($parent);
        $this->apply([
            'in' => 'SourceGraphic',
            'k1' => 0,
            'k2' => 0,
            'k3' => 0,
            'k4' => 0,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feComposite";
    }

}