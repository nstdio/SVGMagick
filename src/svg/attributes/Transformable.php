<?php
namespace nstdio\svg\attributes;

/**
 * interface Transformable
 *
 * @package nstdio\svg\attributes
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Transformable
{
    /**
     * @return string A transform attribute of element.
     */
    public function getTransformAttribute();

    /**
     * @param $transformList
     */
    public function setTransformAttribute($transformList);
}