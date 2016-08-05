<?php
namespace nstdio\svg\attributes;

/**
 * interface Styleable
 *
 * @package nstdio\svg\attributes
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Styleable
{
    /**
     * @param array | string $style
     *
     * @return Styleable
     */
    public function setStyle($style);
}