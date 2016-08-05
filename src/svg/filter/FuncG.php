<?php
namespace nstdio\svg\filter;

/**
 * Class FuncG
 * 
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class FuncG extends Func
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feFuncG";
    }

}