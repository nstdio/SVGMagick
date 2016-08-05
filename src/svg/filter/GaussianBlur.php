<?php
namespace nstdio\svg\filter;

/**
 * Class GaussianBlur
 * This filter primitive performs a Gaussian blur on the input image.
 *
 * @link    https://www.w3.org/TR/SVG11/filters.html#feGaussianBlurElement
 * @property string stdDeviation = "<number-optional-number>" The standard deviation for the blur operation. If two
 *           <number>s are provided, the first number represents a standard deviation value along the x-axis of the
 *           coordinate system established by attribute 'primitiveUnits' on the 'filter' element. The second value
 *           represents a standard deviation in Y. If one number is provided, then that value is used for both X and Y.
 *           A negative value is an error (see Error processing). A value of zero disables the effect of the given
 *           filter primitive (i.e., the result is the filter input image). If 'stdDeviation' is 0 in only one of X or
 *           Y, then the effect is that the blur is only applied in the direction that has a non-zero value. If the
 *           attribute is not specified, then the effect is as if a value of 0 were specified.
 *
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class GaussianBlur extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feGaussianBlur";
    }

}