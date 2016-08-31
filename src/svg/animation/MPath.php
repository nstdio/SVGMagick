<?php
namespace nstdio\svg\animation;
use nstdio\svg\attributes\Core;
use nstdio\svg\attributes\ExternalResourcesRequired;
use nstdio\svg\attributes\XLink;
use nstdio\svg\ElementInterface;

/**
 * Class MPath
 *
 * @package nstdio\svg\animation
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class MPath extends BaseAnimation implements Core, XLink, ExternalResourcesRequired
{

    public function __construct(ElementInterface $parent)
    {
        parent::__construct($parent);
    }

    public function getName()
    {
        return 'mpath';
    }
}