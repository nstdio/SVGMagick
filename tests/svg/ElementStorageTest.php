<?php

use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\ElementStorage;

class ElementStorageTest extends SVGContextTestCase
{
    /**
     * @var ElementStorage
     */
    private $storage;

    public function setUp()
    {
        parent::setUp();
        $this->storage = new ElementStorage();
    }

    public function testEmptyCount()
    {
        self::assertCount(0, $this->storage);

        $this->storage[] = $this->svgObj;

        self::assertCount(1, $this->storage);
    }

    public function testOffsetExistsAndGet()
    {
        self::assertNull($this->storage[0]);
        self::assertNull($this->storage[1]);

        $this->storage[] = $this->svgObj;

        self::assertNotNull($this->storage[0]);
        self::assertEquals($this->svgObj, $this->storage[0]);

    }

    /**
     * @dataProvider                   invalidKeyProvider
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /The offset must be integer, (array|double|boolean|object|string) given./
     */
    public function testOffsetSetInvalidOffset($data)
    {
        $this->storage[$data] = $this->svgObj;
    }

    /**
     * @dataProvider                   diffTypesProvider
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /The value must implement ElementInterface, (integer|array|double|boolean|object|string) given./
     */
    public function testOffsetSetInvalidValue($data)
    {
        $this->storage[] = $data;
    }

    public function testOffsetUnset()
    {
        $this->storage[] = $this->svgObj;

        self::assertNotNull($this->storage[0]);

        unset($this->storage[0]);

        self::assertNull($this->storage[0]);
    }

    /**
     * @dataProvider removeProvider
     */
    public function testRemove($data)
    {
        if (!is_array($data)) {
            self::markTestIncomplete(sprintf("In test %s dataProvider must provide array as data.", __METHOD__));
        }
        foreach ($data as $item) {
            $this->storage[] = $item;
        }
        if (count($data) > 1 && isset($data[0])) {
            $this->storage->remove($data[0]);
            unset($data[0]);
            self::assertEquals($data[1], $this->storage[0]);
        }

        foreach ($data as $item) {
            $this->storage->remove($item);
        }

        self::assertCount(0, $this->storage);
    }

    public function invalidKeyProvider()
    {
        $ret = $this->diffTypesProvider();
        unset($ret['integer']);
        return $ret;
    }

    public function removeProvider()
    {
        $svg = new SVG();
        return [
            'one object' => [[$svg]],
            'multiple objects' => [[$svg, new G($svg), new Defs($svg)]],
        ];
    }

    public function diffTypesProvider()
    {
        return [
            'integer' => [1],
            'string' => ['string'],
            'double'  => [0.1],
            'array'   => [[]],
            'boolean'   => [true],
            'object' => [new stdClass()],
        ];
    }
}
