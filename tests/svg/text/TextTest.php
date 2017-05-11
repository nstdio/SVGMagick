<?php
use nstdio\svg\text\Text;

/**
 * Class TextTest
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class TextTest extends SVGContextTestCase
{
    public function testCreate()
    {
        $text = Text::create($this->svgObj, "text");

        self::assertEquals("text", $text->getElement()->getNodeValue());
    }
}