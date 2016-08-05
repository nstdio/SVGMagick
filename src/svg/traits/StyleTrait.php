<?php
namespace nstdio\svg\traits;
use nstdio\svg\util\KeyValueWriter;

/**
 * trait StyleTrait
 *
 * @package nstdio\svg\traits
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
trait StyleTrait
{
    public function setStyle($style)
    {
        if (is_array($style)) {
            $this->style = KeyValueWriter::styleArrayToString($style);
        } else {
            $this->style = $style;
        }
    }
}