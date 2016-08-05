<?php
namespace nstdio\svg\desc;
use nstdio\svg\ElementInterface;
use nstdio\svg\SVGElement;

/**
 * Class Descriptive
 *
 * @package nstdio\svg\desc
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class Descriptive extends SVGElement
{
    public function __construct(ElementInterface $svg, $value)
    {
        parent::__construct($svg);

        $this->getElement()->nodeValue = $value;
    }
}