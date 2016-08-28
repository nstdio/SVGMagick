<?php
namespace nstdio\svg\filter;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\ElementInterface;
use nstdio\svg\traits\ElementTrait;

/**
 * Class Filter
 *
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Filter extends BaseFilter implements ContainerInterface
{
    use ElementTrait;

    public function __construct(ElementInterface $parent, $id = null)
    {
        $defs = self::getDefs($parent);
        parent::__construct($defs);

        $this->id = $id;
    }

    public function getName()
    {
        return 'filter';
    }

    /**
     * Applies a drop shadow effect to the input image. A drop shadow is effectively a blurred, offset version of the
     * input image's alpha mask drawn in a particular color, composited below the image. The function accepts a
     * parameter of type <shadow> (defined in CSS3 Backgrounds), with the exception that the 'inset' keyword is not
     * allowed. This function is similar to the more established box-shadow property; the difference is that with
     * filters, some browsers provide hardware acceleration for better performance. The parameters of the <shadow>
     * parameter are as follows.
     *
     * @param ContainerInterface $container
     * @param string|float       $offsetX      These are two <length> values to set the shadow offset. <offset-x>
     *                                         specifies the horizontal distance. Negative values place the shadow to
     *                                         the left of the element. <offset-y> specifies the vertical distance.
     *                                         Negative values place the shadow above the element. See <length> for
     *                                         possible units. If both values are 0, the shadow is placed behind the
     *                                         element (and may generate a blur effect if <blur-radius> and/or
     *                                         <spread-radius> is set).
     * @param string|float       $offsetY      Same as offset.
     * @param string|float       $blurRadius   This is a third <length> value. The larger this value, the bigger the
     *                                         blur, so the shadow becomes bigger and lighter. Negative values are not
     *                                         allowed. If not specified, it will be 0 (the shadow's edge is sharp).
     * @param null               $filterId
     *
     * @return Filter
     */
    public static function shadow(ContainerInterface $container, $offsetX, $offsetY = null, $blurRadius = null, $filterId = null)
    {
        $filter = (new Filter($container, $filterId))->apply([
            'x'      => '-50%',
            'y'      => '-50%',
            'width'  => '200%',
            'height' => '200%',
        ]);

        (new Offset($filter))->apply([
            'result' => 'offOut',
            'in'     => 'SourceGraphic',
            'dx'     => $offsetX,
            'dy'     => $offsetY === null ? $offsetX : $offsetY,
        ]);

        (new ColorMatrix($filter))->apply([
            'result' => 'matrixOut',
            'in'     => 'offOut',
            'type'   => 'matrix',
            'values' => '0.1 0 0 0 0 0 0.1 0 0 0 0 0 0.1 0 0 0 0 0 1 0',
        ]);

        (new GaussianBlur($filter))->apply([
            'result'       => "blurOut",
            'stdDeviation' => $blurRadius,
        ]);

        (new Blend($filter))->apply([
            'in'   => 'SourceGraphic',
            'in2'  => 'blurOut',
            'mode' => 'normal',
        ]);

        return $filter;
    }

    /**
     * Converts the input image to grayscale. The value of 'amount' defines the proportion of the conversion. A value
     * of 100% is completely grayscale. A value of 0% leaves the input unchanged. Values between 0% and 100% are linear
     * multipliers on the effect. If the 'amount' parameter is missing, a value of 0 is used.
     *
     * @param ContainerInterface $container
     * @param int|string         $amount
     *
     * @return Filter
     */
    public static function grayScale(ContainerInterface $container, $amount)
    {
        $filter = new Filter($container);

        $amount = intval($amount);

        $amountR = 0.002126 * $amount;
        $amountG = 0.007152 * $amount;
        $amountB = 0.000722 * $amount;

        (new ColorMatrix($filter))->apply([
            'type'   => 'matrix',
            'values' => "$amountR $amountG $amountB 0 0
                       $amountR $amountG $amountB 0 0
                       $amountR $amountG $amountB 0 0
                       0 0 0 1 0",
        ]);

        return $filter;
    }

    /**
     * Inverts the samples in the input image. The value of 'amount' defines the proportion of the conversion. A value
     * of 100% is completely inverted. A value of 0% leaves the input unchanged. Values between 0% and 100% are linear
     * multipliers on the effect. If the 'amount' parameter is missing, a value of 0 is used.
     *
     * @param ContainerInterface $container
     * @param null               $filterId
     *
     * @return Filter
     */
    public static function invert(ContainerInterface $container, $filterId = null)
    {
        return ComponentTransfer::table($container, [[1, 0]], $filterId);
    }

    /**
     * Converts the input image to sepia. The value of 'amount' defines the proportion of the conversion. A value of
     * 100% is completely sepia. A value of 0% leaves the input unchanged. Values between 0% and 100% are linear
     * multipliers on the effect. If the 'amount' parameter is missing, a value of 0 is used.
     *
     * @param ContainerInterface $container
     * @param                    $amount
     * @param null               $filterId
     *
     * @return Filter
     */
    public static function sepia(ContainerInterface $container, $amount, $filterId = null)
    {
        $filter = new Filter($container, $filterId);
        (new ColorMatrix($filter))->apply([
            'type'   => 'matrix',
            'values' => "0.393 0.769 0.189 0 0
                       0.349 0.686 0.168 0 0
                       0.272 0.534 0.131 0 0
                       0 0 0 1 0",
        ]);

        return $filter;
    }
}