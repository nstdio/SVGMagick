<?php
use nstdio\svg\util\transform\Transform;

/**
 * Class TransformCompactTest
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class TransformCompactTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Transform
     */
    private $trans;

    public function setUp()
    {
        $this->init();
    }

    private function init()
    {
        $this->trans = Transform::newInstance();
    }

    public function testCompact()
    {
        $this->trans->translate(10);
        $this->trans->translate(20);
        $this->trans->translate(20);
        $this->trans->scale(1.1, 1.5);
        $this->trans->scale(1.2);
        $this->trans->scale(1.3, 0.01);

        self::assertEquals("translate(50) scale(1.716 0.018)", $this->trans->compact());

    }

    public function testNoCompact()
    {
        $this->trans->translate(20, 20);
        $this->trans->scale(0.5);
        $this->trans->translate(10);

        self::assertEquals("translate(20 20) scale(0.5) translate(10)", $this->trans->compact());
    }

    public function testTranslateXAndY()
    {
        $this->trans->translate(20, 20);
        $this->trans->translate(10);

        self::assertEquals("translate(30 20)", $this->trans->compact());

        $this->init();

        $this->trans->translate(20, 20);
        $this->trans->translate(20, 20);

        self::assertEquals("translate(40 40)", $this->trans->compact());
    }

    public function testRotate()
    {
        $this->trans->rotate(5, 10, 10);
        $this->trans->rotate(5, 20, 20);

        self::assertEquals("rotate(10 15 15)", $this->trans->compact());

        $this->init();

        $this->trans->rotate(5);
        $this->trans->rotate(5, 20, 20);

        self::assertEquals("rotate(10 10 10)", $this->trans->compact());
    }

    public function testSkew()
    {
        $this->trans->skewX(5);
        $this->trans->skewY(5);
        $this->trans->skewX(5);
        $this->trans->skewX(5);

        self::assertEquals("skewX(5) skewY(5) skewX(10)", $this->trans->compact());
    }

    public function testMatrix()
    {
        $this->trans->matrix([1, 0, 0, 1, 25, 25]);
        $this->trans->matrix([1, 0, 0, 1, 25, 25]);

        self::assertEquals("matrix(1 0 0 1 50 50)", $this->trans->compact());
    }

    public function testTranslateToMatrix()
    {
        $this->trans->translate(25, 50);

        self::assertEquals("matrix(1 0 0 1 25 50)", $this->trans->toMatrix());
    }

    public function testScaleToMatrix()
    {
        $this->trans->scale(10);

        self::assertEquals("matrix(10 0 0 10 0 0)", $this->trans->toMatrix());
    }
}