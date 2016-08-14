<?php
namespace nstdio\svg\filter;

use nstdio\svg\ElementInterface;

/**
 * Class Func
 *
 * @property string type
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Func extends BaseFilter
{
    public function __construct(ElementInterface $parent, $type)
    {
        parent::__construct($parent);
        $this->type = $type;
    }

}