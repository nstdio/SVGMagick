<?php
namespace nstdio\svg\filter;

/**
 * Class MergeNode
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class MergeNode extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feMergeNode";
    }

}