<?php
namespace nstdio\svg\filter;

/**
 * Class Flood
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Flood extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feFlood";
    }

}