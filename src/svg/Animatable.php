<?php
namespace nstdio\svg;
use nstdio\svg\animation\BaseAnimation;

/**
 * interface Animatable
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Animatable
{
    public function animate(BaseAnimation $animation);
}