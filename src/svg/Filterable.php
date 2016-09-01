<?php
namespace nstdio\svg;


use nstdio\svg\filter\BaseFilter;

/**
 * Interface Animatable
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface Filterable
{
    /**
     * @param BaseFilter $elements
     *
     * @return $this
     */
    public function applyFilter(BaseFilter $elements);
}