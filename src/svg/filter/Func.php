<?php
namespace nstdio\svg\filter;
use nstdio\svg\ElementInterface;

/**
 * Class Func
 *
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Func extends BaseFilter
{
    public function __construct(ElementInterface $svg, $type)
    {
        parent::__construct($svg);
        $this->type = $type;
    }

}