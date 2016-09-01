<?php
namespace nstdio\svg;
use nstdio\svg\animation\BaseAnimation;

/**
 * Interface Animatable
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Animatable
{
    /**
     * @param BaseAnimation $animation
     *
     * @return $this
     */
    public function animate(BaseAnimation $animation);
}