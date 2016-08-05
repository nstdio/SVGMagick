<?php
namespace nstdio\svg\filter;

/**
 * Class Blend
 * This filter composites two objects together using commonly used imaging software blending modes. It performs a
 * pixel-wise combination of two input images.
 *
 * @link    https://www.w3.org/TR/SVG11/filters.html#feBlendElement
 * @property string mode = "normal | multiply | screen | darken | lighten" One of the image blending modes (see
 *           {@link https://www.w3.org/TR/SVG11/filters.html#BlendingTable}). If attribute 'mode' is not specified,
 *           then the effect is as if a value of normal were specified.
 * @property string in2  The second input image to the blending operation. This attribute can take on the same values
 *           as the 'in' attribute.
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Blend extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feBlend";
    }

}