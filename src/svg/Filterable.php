<?php
namespace nstdio\svg;


use nstdio\svg\filter\BaseFilter;

interface Filterable
{
    public function applyFilter(BaseFilter $elements);
}