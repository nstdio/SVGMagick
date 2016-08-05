<?php
namespace nstdio\svg\shape;

use nstdio\svg\Animatable;
use nstdio\svg\animation\BaseAnimation;
use nstdio\svg\attributes\ConditionalProcessing;
use nstdio\svg\attributes\Core;
use nstdio\svg\attributes\ExternalResourcesRequired;
use nstdio\svg\attributes\GraphicalEvent;
use nstdio\svg\attributes\Presentation;
use nstdio\svg\attributes\Styleable;
use nstdio\svg\attributes\Transformable;
use nstdio\svg\container\ContainerInterface;
use nstdio\svg\filter\BaseFilter;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\Filterable;
use nstdio\svg\gradient\Gradient;
use nstdio\svg\SVGElement;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\traits\StyleTrait;

/**
 * Class Shape
 *
 * @property float  height      The height of shape.
 * @property float  width       The width of shape.
 * @property string stroke      Stroke color.
 * @property float  strokeWidth Width of stroke.
 * @property string strokeLocation
 * @property string style
 * @property string fill
 * @property float  fillOpacity specifies the opacity of the painting operation used to paint the interior the current
 *           object.
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Shape extends SVGElement implements ContainerInterface, ConditionalProcessing, Core, GraphicalEvent, Presentation, Styleable, Transformable, ExternalResourcesRequired, Animatable, Filterable
{
    use StyleTrait, ElementTrait;

    public function applyGradient(Gradient $gradient)
    {
        $this->fill = "url(#{$gradient->id})";

        return $this;
    }

    public function animate(BaseAnimation $animation)
    {
        $this->element->appendChild($animation->getElement());

        return $this;
    }

    public function filterGaussianBlur($stdDeviation, $in = null)
    {
        $blur = new GaussianBlur($this);
        $filter = new Filter($this, null, $blur);
        $blur->stdDeviation = $stdDeviation;
        $blur->in = $in;

        $this->getRoot()->append($filter);
        $this->applyFilter($filter);

        return $this;
    }

    public function applyFilter(BaseFilter $filter)
    {
        $this->filter = "url(#$filter->id)";

        return $this;
    }
}