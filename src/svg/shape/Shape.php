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
use nstdio\svg\gradient\UniformGradient;
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
                $filterRoot = $filter->getRoot();
                $filterRoot->removeChild($filter);
            }
        }

        return $this;
    }

    public function linearGradientFromTop(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::verticalFromTop($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromBottom(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::verticalFromBottom($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromLeft(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::horizontalFromLeft($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromRight(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::horizontalFromRight($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromTopLeft(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::diagonalFromTopLeft($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromTopRight(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::diagonalFromTopRight($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromBottomLeft(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::diagonalFromBottomLeft($this->getRoot(), $colors, $id));
    }

    public function linearGradientFromBottomRight(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::diagonalFromBottomRight($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromTopLeft(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialTopLeft($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromTopRight(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialTopRight($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromBottomLeft(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialBottomLeft($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromBottomRight(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialBottomRight($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromTopCenter(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialTopCenter($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromLeftCenter(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialLeftCenter($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromBottomCenter(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialBottomCenter($this->getRoot(), $colors, $id));
    }

    public function radialGradientFromRightCenter(array $colors, $id = null)
    {
        return $this->applyGradient(UniformGradient::radialRightCenter($this->getRoot(), $colors, $id));
    }
}