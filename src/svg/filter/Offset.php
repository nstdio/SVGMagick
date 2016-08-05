<?php
namespace nstdio\svg\filter;

/**
 * Class Offset
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Offset extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feOffset";
    }

}