<?php

use nstdio\svg\container\G;
use nstdio\svg\util\Identifier;

class IdentifierTest extends SVGContextTestCase
{
    private $prefix;

    private $len;

    public function setUp()
    {
        parent::setUp();
        $this->prefix = '__svg';
        $this->len = 10;
    }

    public function testRandom()
    {
        $rand = Identifier::random($this->prefix, $this->len);

        self::assertStringStartsWith($this->prefix, $rand);
        self::assertEquals($this->len + strlen($this->prefix), strlen($rand));
    }

    public function testFallbackLength()
    {
        $len = "use_fallback_1";
        $rand = Identifier::random($this->prefix, $len);
        self::assertEquals(strlen($this->prefix) + Identifier::FALLBACK_LENGTH, strlen($rand));

        $len = "4_use_two";
        $rand = Identifier::random($this->prefix, $len);
        self::assertEquals(strlen($this->prefix) + intval($len), strlen($rand));

        $len = "-2_use_fallback_too";
        $rand = Identifier::random($this->prefix, $len);
        self::assertEquals(strlen($this->prefix) + Identifier::FALLBACK_LENGTH, strlen($rand));

        $len = [1, 2, 3, 5, 7, 11];
        $rand = Identifier::random($this->prefix, $len);
        self::assertEquals(strlen($this->prefix) + Identifier::FALLBACK_LENGTH, strlen($rand));
    }

    public function testSequential()
    {
        for ($i = 0; $i < 50; $i++) {
            $group = new G($this->svgObj);
            $group->id = Identifier::sequential($this->svgObj, 'g');
            $this->svgObj->append($group);

            self::assertEquals('g' . ($i + 2), $group->id);
        }
    }
}
