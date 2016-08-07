<?php
use nstdio\svg\container\ContainerInterface;
use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\shape\Rect;

/**
 * Class SVGChildTest
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class SVGChildTest extends SVGContextTestCase
{
    private static $depth;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    public function setUp()
    {
        parent::setUp();
        self::assertAttributeCount(0, 'child', $this->svgObj);
    }

    public function testFindChild()
    {
        $group = new G($this->svgObj);
        $group->id = 'group';
        $count = 100;
        for ($i = 0; $i < $count; $i++) {
            new Rect($group, 0, 0);
        }
        $group2 = new G($group);
        $group2->id = 'group2';
        $group3 = new G($group2);
        for ($i = 0; $i < $count; $i++) {
            new Rect($group3, 0, 0);
        }

        $children = $this->svgObj->getChild('rect');
        foreach ($children as $item) {
            self::assertInstanceOf(get_class(new Rect($group, 0, 0)), $item);
        }

        $childrenGroup = $this->svgObj->getChild('g');
        foreach ($childrenGroup as $item) {
            self::assertInstanceOf(get_class($group), $item);
        }

        self::assertEquals(2 * $count, count($children));
        self::assertEquals(3, count($childrenGroup));

        $group4 = new G($this->svgObj);


        self::assertFalse($group4->hasChild());
        self::assertNull($group4->getChildById('id'));

        $group5 = new G($group4);
        self::assertEmpty($group5->getChild('id'));

    }

    public function testGetChildById()
    {
        self::assertAttributeCount(0, 'child', $this->svgObj);

        $group = new G($this->svgObj);
        $group->id = 'g1';

        self::assertAttributeCount(1, 'child', $this->svgObj);
        self::assertAttributeCount(0, 'child', $group);
        self::assertEquals($group->id, $this->svgObj->getChildById($group->id)->id);
        self::assertSame($group, $this->svgObj->getChildById($group->id));

        $group2 = new G($group);
        $group2->id = 'g2';

        self::assertAttributeCount(1, 'child', $this->svgObj);
        self::assertAttributeCount(1, 'child', $group);
        self::assertAttributeCount(0, 'child', $group2);

        self::assertEquals($group2->id, $group->getChildById($group2->id)->id);
        self::assertSame($group2, $group->getChildById($group2->id));
        self::assertEquals($group2->id, $this->svgObj->getChildById($group2->id)->id);

        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $group3 = new G($group2);
            $group3->id = null;

            self::assertNotNull($this->svgObj->getChildById($group3->id));
            self::assertEquals($group3, $this->svgObj->getChildById($group3->id));
            self::assertSame($group3, $this->svgObj->getChildById($group3->id));

            $jStop = mt_rand(1, 2 * $count);

            for ($j = 0; $j < $jStop; $j++) {
                $group4 = new G($group3);
                $group4->id = null;
                self::assertNotNull($this->svgObj->getChildById($group4->id));
                self::assertEquals($group4, $this->svgObj->getChildById($group4->id));
                self::assertSame($group4, $this->svgObj->getChildById($group4->id));
            }
        }
    }

    public function testGetChildByIdInDepth()
    {
        $nestingLevel = ini_get('xdebug.max_nesting_level');
        ini_set('xdebug.max_nesting_level', '512');

        $element = $this->deepAppend($this->svgObj, 100);
        self::$depth = 0;
        $element2 = $this->deepAppend($element, 65);
        self::$depth = 0;
        $deepestElement = $this->deepAppend($element2, 35);

        self::assertNotNull($deepestElement);
        self::assertEquals($deepestElement->id, $this->svgObj->getChildById($deepestElement->id)->id);

        ini_set('xdebug.max_nesting_level', $nestingLevel);
    }

    public function testFirstChild()
    {
        self::assertNull($this->svgObj->getFirstChild());

        $defs = new Defs($this->svgObj);
        $g = new G($this->svgObj);
        $g2 = new G($g);

        self::assertEquals($defs, $this->svgObj->getFirstChild());
        self::assertEquals($g2, $g->getFirstChild());
        self::assertNotEquals($g, $this->svgObj->getFirstChild());
    }

    public function testGetChildrenAndAtIndex()
    {
        self::assertEmpty($this->svgObj->getChildren());

        $count = 50;
        $children = [];
        for ($i = 0; $i < $count; $i++) {
            $rect = new Rect($this->svgObj, 0, 0);
            $rect->id = null;
            $children[] = $rect;
        }

        foreach ($this->svgObj->getChildren() as $key => $item) {
            self::assertEquals($children[$key], $item);
            self::assertEquals($children[$key], $this->svgObj->getChildAtIndex($key));
            self::assertSame($children[$key], $item);
        }

        self::assertNull($this->svgObj->getChildAtIndex($count + 1));
        self::assertNull($this->svgObj->getChildAtIndex(-1));
    }

    private function deepAppend(ContainerInterface $parent, $depth)
    {
        $g = new G($parent);
        $g->id = null;

        if (self::$depth < $depth) {
            self::$depth++;
            return $this->deepAppend($g, $depth);
        } else {
            return $g;
        }
    }
}