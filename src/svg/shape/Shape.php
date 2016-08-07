<?php
namespace nstdio\svg\shape;

use nstdio\svg\Animatable;
use nstdio\svg\animation\BaseAnimation;
use nstdio\svg\attributes\Styleable;
use nstdio\svg\filter\BaseFilter;
use nstdio\svg\filter\DiffuseLighting;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\Filterable;
use nstdio\svg\gradient\Gradient;
use nstdio\svg\SVGElement;
use nstdio\svg\traits\StyleTrait;

/**
 * Class Shape
 *
 * @property float       height      The height of shape.
 * @property float       width       The width of shape.
 * @property string      stroke      Stroke color.
 * @property float       strokeWidth Width of stroke.
 * @property string      strokeLocation
 * @property string      style
 * @property string      fill
 * @property float       fillOpacity specifies the opacity of the painting operation used to paint the interior the
 *           current
 * @property string      filter
 * @property string|null filterUrl The url part of filter.
 *           object.
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Shape extends SVGElement implements Styleable, Animatable, Filterable
{
    use StyleTrait;

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

    public function filterGaussianBlur($stdDeviation, $in = null, $filterId = null)
    {
        $filter = new Filter($this->getRoot(), $filterId);
        $blur = new GaussianBlur($filter);
        $blur->stdDeviation = $stdDeviation;
        $blur->in = $in;

        $this->applyFilter($filter);

        return $this;
    }

    public function diffusePointLight(array $pointLightConfig = [], array $diffuseLightingConfig = [], $filterId = null)
    {
        $pointConfig = [
            'x' => $this->x,
            'y' => $this->y,
            'z' => 10,
        ];
        foreach ($pointConfig as $key => $value) {
            if (isset($pointLightConfig[$key])) {
                $pointConfig[$key] = $this->{$key} + $pointLightConfig[$key];
            }
        }
        $filter = DiffuseLighting::diffusePointLight($this->getRoot(), $pointConfig, $diffuseLightingConfig, $filterId);
        $this->applyFilter($filter);

        return $this;
    }

    public function applyFilter(BaseFilter $filter)
    {
        if ($this->filter === null) {
            $this->filter = "url(#$filter->id)";
        } else {
            $value = str_replace(['url(#', ')'], '', $this->filter);
            $svg = $this->getSVG();
            $currentFilter = $svg->getChildById($value);
            if ($currentFilter !== null) {
                foreach ($filter->getChildren() as $child) {
                    $currentFilter->append($child);
                }
                // TODO: remove filter from dom and from children list.
            }
        }

        return $this;
    }
}