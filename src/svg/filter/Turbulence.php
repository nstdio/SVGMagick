<?php
namespace nstdio\svg\filter;

/**
 * Class Turbulence
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Turbulence extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feTurbulence";
    }

}