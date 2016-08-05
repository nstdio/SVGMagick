<?php

use nstdio\svg\container\Defs;
use nstdio\svg\filter\ComponentTransfer;

class ComponentTransferTest extends SVGContextTestCase
{
    /**
     *
     */
    private $defs;

    public function setUp()
    {
        parent::setUp();
        $this->defs = new Defs($this->svgObj);
    }

    public function testCorrectTable()
    {
        $table = [
            [0, 1, 1, 0, 0],
            [1, 0, 0, 1, 0],
            [1, 0, 1, 0, 0],
        ];
        $filter = ComponentTransfer::table($this->defs, $table);

        for ($i = 0; $i < $filter->getElement()->getElement()->firstChild->childNodes->length; $i++) {
            self::assertEquals(implode(' ', $table[$i]), $filter->getElement()->getElement()->firstChild->childNodes->item($i)->getAttribute('tableValues'));
        }

        $table = [
            'r' => [0, 1, 1, 0, 0],
            'g' => [1, 0, 0, 1, 0],
            'b' => [1, 0, 1, 0, 0],
        ];
        $filter = ComponentTransfer::table($this->defs, $table);

        self::assertEquals(implode(' ', $table['r']), $filter->getElement()->getElement()->firstChild->firstChild->getAttribute('tableValues'));
        self::assertEquals(implode(' ', $table['g']), $filter->getElement()->getElement()->firstChild->childNodes->item(1)->getAttribute('tableValues'));
        self::assertEquals(implode(' ', $table['b']), $filter->getElement()->getElement()->firstChild->childNodes->item(2)->getAttribute('tableValues'));

        $table = [
            [0, 1, 1, 0, 0],
            [1, 0, 0, 1, 0],
        ];
        $filter = ComponentTransfer::table($this->defs, $table);
        $table[] = $table[1];
        for ($i = 0; $i < $filter->getElement()->firstChild->childNodes->length; $i++) {
            self::assertEquals(implode(' ', $table[$i]), $filter->getElement()->getElement()->firstChild->childNodes->item($i)->getAttribute('tableValues'));
        }

        $table = [
            'r' => [0, 1, 1, 0, 0],
            'g' => [1, 0, 0, 1, 0],
        ];
        $filter = ComponentTransfer::table($this->defs, $table);
        $table['b'] = $table['g'];

        self::assertEquals(implode(' ', $table['r']), $filter->getElement()->getElement()->firstChild->firstChild->getAttribute('tableValues'));
        self::assertEquals(implode(' ', $table['g']), $filter->getElement()->getElement()->firstChild->childNodes->item(1)->getAttribute('tableValues'));
        self::assertEquals(implode(' ', $table['b']), $filter->getElement()->getElement()->firstChild->childNodes->item(2)->getAttribute('tableValues'));
    }

    /**
     *
     */
    public function testLinearWithString()
    {
        $filter = ComponentTransfer::linear($this->defs, '.5', '.25');

        $len = $filter->getElement()->getElement()->firstChild->childNodes->length;
        self::assertEquals(3, $len);

        for ($i = 0; $i < $len; $i++) {
            /** @var DOMElement $item */
            $item = $filter->getElement()->getElement()->firstChild->childNodes->item($i);
            self::assertEquals('linear', $item->getAttribute('type'));
            self::assertEquals('.5', $item->getAttribute('slope'));
            self::assertEquals('.25', $item->getAttribute('intercept'));
        }
    }

    public function testLinearWithArray()
    {
        $slope = ['.5', '.2', '.7'];
        $intercept = ['.25'];
        $filter = ComponentTransfer::linear($this->defs, $slope, $intercept);

        $intercept[] = $intercept[0];
        $intercept[] = $intercept[0];
        $len = $filter->getElement()->getElement()->firstChild->childNodes->length;
        self::assertEquals(3, $len);

        for ($i = 0; $i < $len; $i++) {
            /** @var DOMElement $item */
            $item = $filter->getElement()->getElement()->firstChild->childNodes->item($i);
            self::assertEquals('linear', $item->getAttribute('type'));
            self::assertEquals($slope[$i], $item->getAttribute('slope'));
            self::assertEquals($intercept[$i], $item->getAttribute('intercept'));
        }
    }

    public function testIdentity()
    {
        $filter = ComponentTransfer::identity($this->defs);

        $len = $filter->getElement()->getElement()->firstChild->childNodes->length;
        self::assertEquals(4, $len);

        for ($i = 0; $i < $len; $i++) {
            /** @var DOMElement $item */
            $item = $filter->getElement()->getElement()->firstChild->childNodes->item($i);
            self::assertEquals('identity', $item->getAttribute('type'));
        }
    }

    public function testGamma()
    {
        $amplitude = 2;
        $exp = [5, 3];
        $offset = 0;
        $filter = ComponentTransfer::gamma($this->defs, $amplitude, $exp, $offset);
        $exp[] = $exp[1];
        $len = $filter->getElement()->getElement()->firstChild->childNodes->length;
        self::assertEquals(3, $len);

        for ($i = 0; $i < $len; $i++) {
            /** @var DOMElement $item */
            $item = $filter->getElement()->getElement()->firstChild->childNodes->item($i);
            self::assertEquals('gamma', $item->getAttribute('type'));
            self::assertEquals($amplitude, $item->getAttribute('amplitude'));
            self::assertEquals($exp[$i], $item->getAttribute('exponent'));
            self::assertEquals($offset, $item->getAttribute('offset'));
        }
    }

    public function testBrightness()
    {
        $amount = 5;
        $filter = ComponentTransfer::brightness($this->defs, $amount);
        $len = $filter->getElement()->getElement()->firstChild->childNodes->length;

        self::assertEquals(3, $len);

        for ($i = 0; $i < $len; $i++) {
            /** @var DOMElement $item */
            $item = $filter->getElement()->getElement()->firstChild->childNodes->item($i);
            self::assertEquals('linear', $item->getAttribute('type'));
            self::assertEquals($amount, $item->getAttribute('slope'));
        }
    }

    public function testContrast() {
        $amount = 20;
        $intercept = 0.5 - (0.5 * $amount);
        $filter = ComponentTransfer::contrast($this->defs, $amount);
        $len = $filter->getElement()->getElement()->firstChild->childNodes->length;

        for ($i = 0; $i < $len; $i++) {
            /** @var DOMElement $item */
            $item = $filter->getElement()->getElement()->firstChild->childNodes->item($i);
            self::assertEquals('linear', $item->getAttribute('type'));
            self::assertEquals($amount, $item->getAttribute('slope'));
            self::assertEquals($intercept, $item->getAttribute('intercept'));
        }
    }
}
