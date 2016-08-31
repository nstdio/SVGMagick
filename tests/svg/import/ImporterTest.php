<?php

use nstdio\svg\import\Importer;

class ImporterTest extends SVGContextTestCase
{
    /**
     * @var Importer
     */
    private $importer;

    public function setUp()
    {
        parent::setUp();
        $this->importer = new Importer();
    }

    public function testNoSvgString()
    {
        $actual = $this->importer->fromString("<html><body><p>There is no svg inside.</p></body></html>");

        self::assertNull($actual);
    }

    public function testEmptyImport()
    {
        $svg = $this->importer->fromString($this->svgObj->draw());

        self::assertEquals($svg, $this->svgObj);
    }
}
