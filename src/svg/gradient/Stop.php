<?php
namespace nstdio\svg\gradient;

use nstdio\svg\attributes\Core;
use nstdio\svg\attributes\Presentation;
use nstdio\svg\attributes\Styleable;
use nstdio\svg\ElementInterface;
use nstdio\svg\SVGElement;
use nstdio\svg\traits\StyleTrait;
use nstdio\svg\util\KeyValueWriter;

/**
 * Class Stop
 *
 * The ramp of colors to use on a gradient is defined by the 'stop' elements that are child elements to either the
 * 'linearGradient' element or the 'radialGradient' element.
 *
 * @link     https://www.w3.org/TR/SVG11/pservers.html#GradientStops
 * @property string $offset    "<number> | <percentage>"
 *           The 'offset' attribute is either a <number> (usually ranging from 0 to 1) or a <percentage> (usually
 *           ranging from 0% to 100%) which indicates where the gradient stop is placed. For linear gradients, the
 *           'offset' attribute represents a location along the gradient vector. For radial gradients, it represents a
 *           percentage distance from (fx,fy) to the edge of the outermost/largest circle.
 * @property string $stopColor The 'stop-color' property indicates what color to use at that gradient stop. The keyword
 *           currentColor and ICC colors can be specified in the same manner as within a <paint> specification for the
 *           'fill' and 'stroke' properties.
 * @property string $stopOpacity The 'stop-opacity' property defines the opacity of a given gradient stop.
 * @package  nstdio\svg\gradient
 * @author   Edgar Asatryan <nstdio@gmail.com>
 */
class Stop extends SVGElement implements Core, Presentation, Styleable
{
    use StyleTrait;

    public function __construct(ElementInterface $parent, $config = [])
    {
        parent::__construct($parent);

        KeyValueWriter::apply($this->element, $config);
    }

    public function getName()
    {
        return 'stop';
    }
}