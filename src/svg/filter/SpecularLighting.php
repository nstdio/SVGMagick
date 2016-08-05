<?php
namespace nstdio\svg\filter;

/**
 * Class SpecularLighting
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SpecularLighting extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feSpecularLighting";
    }

}