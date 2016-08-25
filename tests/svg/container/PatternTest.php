<?php

use nstdio\svg\container\Pattern;

class PatternTest extends SVGContextTestCase
{
    /**
     * @var Pattern
     */
    private $pattern;

    public function setUp()
    {
        parent::setUp();
        $this->pattern = new Pattern($this->svgObj);
    }

    public function testAutoAppendToDefs()
    {
        self::assertEquals('defs', $this->pattern->getRoot()->getName());
    }

    public function testId()
    {
        $patternId = 'ptrn';
        $pattern = new Pattern($this->svgObj);
        $pattern->id = $patternId;

        self::assertEquals($patternId, $pattern->id);
        self::assertNotNull($this->pattern->id);
    }

    public function testRotate()
    {
        $this->pattern->rotate(-10);

    }
}
