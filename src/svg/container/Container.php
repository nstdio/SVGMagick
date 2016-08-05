<?php
namespace nstdio\svg\container;
use nstdio\svg\SVGElement;
use nstdio\svg\traits\ElementTrait;

/**
 * Class Container
 *
 * @package svg\container
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Container extends SVGElement implements ContainerInterface
{
    use ElementTrait;
}