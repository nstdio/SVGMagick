<?php
use nstdio\svg\xml\DOMWrapper;

/**
 * Class DOMContextTest
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class DOMContextTest extends SVGContextTestCase
{

    /**
     * @inheritdoc
     */
    protected function getDomImpl()
    {
        return new DOMWrapper();
    }
}