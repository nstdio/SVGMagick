<?php
namespace nstdio\svg\filter;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\traits\ElementTrait;

/**
 * Class Merge
 *
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Merge extends BaseFilter implements ContainerInterface
{
    use ElementTrait;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feMerge";
    }

}