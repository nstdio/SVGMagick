<?php

use nstdio\svg\output\IOFormat;

class SVGOutputHeadersTest extends SVGContextTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testHeaders()
    {
        $this->svgObj->asSVGZ(true);

        self::assertContains('Content-Encoding: gzip', xdebug_get_headers());
    }

    /**
     * @covers \nstdio\svg\output\ImageOutput::image()
     * @runInSeparateProcess
     */
    public function testImageHeaders()
    {
        try {
            $this->svgObj->asImage(IOFormat::PNG, true);

            self::assertContains('Content-Type: image/png', xdebug_get_headers());

        } catch (RuntimeException $e) {
            self::markTestSkipped($e->getMessage() . "\nSkipping...");
        }
    }
}
