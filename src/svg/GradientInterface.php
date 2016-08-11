<?php
namespace nstdio\svg;

use nstdio\svg\gradient\Gradient;

/**
 * Interface GradientInterface
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface GradientInterface
{
    /**
     * Apply gradient to element.
     * @param Gradient $gradient
     *
     * @return mixed
     */
    public function applyGradient(Gradient $gradient);
}