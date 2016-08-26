<?php
namespace nstdio\svg\util;


interface TransformInterface
{
    const TRANSFORM_ARG_DELIM_SPACE = ' ';

    const TRANSFORM_ARG_DELIM_COMMA = ', ';

    const TRANSFORM_ARG_DELIM_COMMA_SPACE = ',';

    /**
     * @return string The transform string.
     */
    public function result();

    /**
     * Specifies how the transformation arguments will be separated from each other.
     * Default is ARG_DELIM_SPACE.
     *
     * @param string $delim The one of ARG_DELIM_ constants otherwise exception will be thrown.
     *
     * @throws \InvalidArgumentException When $delim is differ from ARG_DELIM_-s
     * @return void
     */
    public function setArgumentDelimiter($delim);

    /**
     * @param float|int $angle Specifies a rotation by degrees about a given point.
     * @param float|int $cx    If optional parameters $cx and $cy are supplied, the rotate is about the point ($cx,
     *                         $cy).
     * @param float|int $cy    See $cx.
     *
     * @return string The changed transform string.
     */
    public function rotate($angle, $cx = null, $cy = null);

    /**
     * @param float|int $x The distances to translate coordinates in X.
     * @param float|int $y The distances to translate coordinates in Y. If not provided, it is assumed to be zero.
     *
     * @return string The changed transform string.
     */
    public function translate($x, $y = null);

    /**
     * Specifies a scale operation by $x and $y. If $y is not provided, it is assumed to be equal to $x.
     *
     * @param float|int $x
     * @param float|int $y
     *
     * @return string The changed transform string.
     */
    public function scale($x, $y = null);

    /**
     * @param float|int $x
     *
     * @return string The changed transform string.
     */
    public function skewX($x);

    /**
     * @param float|int $y
     *
     * @return string The changed transform string.
     */
    public function skewY($y);

    /**
     * Applying matrix transformation.
     *
     * @param array $matrix The array should have exactly 6 elements otherwise exception will be thrown.
     *
     * @throws \InvalidArgumentException When $matrix have less or more then 6 elements.
     * @return string The changed transform string.
     */
    public function matrix(array $matrix);
}