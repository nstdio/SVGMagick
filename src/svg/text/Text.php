<?php
namespace nstdio\svg\text;

use nstdio\svg\ElementInterface;

/**
 * Class Text
 *
 * @package nstdio\svg\text
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Text extends BaseText
{
    public function __construct(ElementInterface $parent, $value)
    {
        parent::__construct($parent);
        $this->element->setNodeValue($value);
    }

    public function getName()
    {
        return 'text';
    }
}