<?php
namespace nstdio\svg\filter;

/**
 * Class Tile
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Tile extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feTile";
    }

}