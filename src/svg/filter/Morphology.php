<?php
namespace nstdio\svg\filter;

/**
 * Class Morphology
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Morphology extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feMorphology";
    }

}