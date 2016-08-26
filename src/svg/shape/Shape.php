<?php
namespace nstdio\svg\shape;

use nstdio\svg\animation\BaseAnimation;
use nstdio\svg\ElementInterface;
use nstdio\svg\filter\BaseFilter;
use nstdio\svg\filter\DiffuseLighting;
use nstdio\svg\filter\Filter;
use nstdio\svg\filter\GaussianBlur;
use nstdio\svg\gradient\Gradient;
use nstdio\svg\gradient\UniformGradient;
use nstdio\svg\SVGElement;
use nstdio\svg\traits\StyleTrait;
use nstdio\svg\traits\TransformTrait;
use nstdio\svg\util\Transform;

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
 * @property string fillUrl     The id part of fill attribute.
 * @property float  fillOpacity specifies the opacity of the painting operation used to paint the interior the
 *           current
 * @property string filter
 * @property string filterUrl   The id part of filter attribute.
 * @property string transform
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Shape extends SVGElement implements ShapeInterface
{
    use StyleTrait, TransformTrait;

    abstract protected function getCenterX();

    abstract protected function getCenterY();

    abstract public function getBoundingBox();

    public function __construct(ElementInterface $parent)
    {
        parent::__construct($parent);

        $this->transformImpl = Transform::newInstance($this->getTransformAttribute());
    }

    /**
     * @inheritdoc
     */
    public function getTransformAttribute()
    {
        return $this->transform;
    }

    public function setTransformAttribute($transformList)
    {
        $this->transform = $transformList;
    }

    public function applyGradient(Gradient $gradient)
    {
        $this->fillUrl = $gradient->id;

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
            'x' => $this->getCenterX(),
            'y' => $this->getCenterY(),
            'z' => 25,
        ];
        foreach ($pointConfig as $key => $value) {
            if (isset($pointLightConfig[$key])) {
                $pointConfig[$key] = $value + $pointLightConfig[$key];
            }
        }
        $filter = DiffuseLighting::diffusePointLight($this->getRoot(), $pointConfig, $diffuseLightingConfig, $filterId);
        $this->applyFilter($filter);

        return $this;
    }

    public function applyFilter(BaseFilter $filter)
    {
        $svg = $this->getSVG();
        if ($this->filter === null) {
            $this->filterUrl = $filter->id;
            $defs = $svg->getFirstChild();
            $filter->selfRemove();
            $defs->append($filter);
        } else {
            $currentFilter = $svg->getFirstChild()->getChildById($this->filterUrl);
            if ($currentFilter !== null) {
                foreach ($filter->getChildren() as $child) {
                    $currentFilter->append($child);
                }
                $filter->selfRemove();
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