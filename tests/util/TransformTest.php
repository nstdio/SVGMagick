<?php

use nstdio\svg\util\Transform;
use nstdio\svg\util\TransformInterface;

class TransformTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid delimiter. See TransformInterface::setArgumentDelimiter documentation for valid value.
     */
    public function testDelimException()
    {
        $trans = Transform::newInstance();

        $trans->setArgumentDelimiter('invalid_delimiter');
    }

    public function testArgumentDelimiter()
    {
        $trans = Transform::newInstance();
        $trans->setArgumentDelimiter(TransformInterface::ARG_DELIM_COMMA);

        $trans->translate(30, 60);
        $trans->rotate(30, 80, 90);

        self::assertEquals("translate(30, 60) rotate(30, 80, 90)", $trans->result());
    }

    /**
     * @dataProvider rotateProvider
     *
     * @param $data
     */
    public function testRotate($data)
    {
        $trans = Transform::newInstance($data['transform']);

        self::assertEquals($data['expected'], $trans->getData()['rotate']);
    }

    public function testModifyRotate()
    {
        $angle = 90;
        $cx = 45;
        $cy = -45;

        $trans = Transform::newInstance("rotate($angle $cx $cy)");

        $newAngle = $angle / 2;
        $expected = "rotate($newAngle $cx $cy)";

        self::assertEquals($expected, $trans->rotate($newAngle));

    }

    public function testInitialRotate()
    {
        $trans = Transform::newInstance();
        $angle = 45;

        self::assertEquals("rotate($angle)", $trans->rotate($angle));
        self::assertEquals("rotate(75.25)", $trans->rotate(75.25));
        self::assertEquals("rotate($angle 0 0)", $trans->rotate($angle, 0));
        self::assertEquals("rotate($angle 0 1)", $trans->rotate(null, 0, 1));
        self::assertEquals("rotate($angle 1 1)", $trans->rotate($angle, null, 1));
    }

    public function testTranslate()
    {
        $trans = Transform::newInstance();

        self::assertEquals("translate(40)", $trans->translate(40));
        self::assertEquals("translate(40.5)", $trans->translate(40.5));
        self::assertEquals("translate(40 5)", $trans->translate(40, 5));

        $trans = Transform::newInstance($trans->translate(40, 10));

        self::assertEquals("translate(90 45)", $trans->translate(90, 45));
    }

    public function testScale()
    {
        $trans = Transform::newInstance();

        self::assertEquals("scale(1.5)", $trans->scale(1.5));
        self::assertEquals("scale(0.5 0.8)", $trans->scale(0.5, 0.8));

        $trans = Transform::newInstance("scale(2)");

        self::assertEquals("scale(2)", $trans->result());
        self::assertEquals("scale(0.5)", $trans->scale(0.5));
    }

    public function testSkew()
    {
        $trans = Transform::newInstance("skewX(0.2) skewY(1)");

        self::assertEquals("skewX(0.2) skewY(1)", $trans->result());
        self::assertEquals("skewX(1.5) skewY(1)", $trans->skewX(1.5));
        self::assertEquals("skewX(20) skewY(1)", $trans->skewX(20));
        self::assertEquals("skewX(20) skewY(20)", $trans->skewY(20));

        $trans->skewY(1.8);
        $trans->skewX(1.8);

        self::assertEquals("skewX(1.8) skewY(1.8)", $trans->result());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidMatrix()
    {
        $trans = Transform::newInstance();
        $trans->matrix(range(0, 7));
    }

    /**
     * @expectedException InvalidArgumentException
     * @dataProvider invalidMatrixProvider
     *
     * @param array $data
     */
    public function testInvalidMatrixImported($data)
    {
        Transform::newInstance($data[0]);
    }

    public function testMatrix()
    {
        $trans = Transform::newInstance();

        self::assertEquals("matrix(1 1 1 0 0 0)", $trans->matrix([1, 1, 1, 0, 0, 0]));
        self::assertEquals("matrix(1 1 1 1 1 1)", $trans->matrix([1, 1, 1, 1, 1, 1]));

        $trans = Transform::newInstance($trans->result());

        self::assertEquals("matrix(1 1 1 1 1 1)", $trans->result());
    }

    public function testTransform()
    {
        $trans = Transform::newInstance("translate(30 60) rotate(15) scale(1.5)");

        self::assertEquals("translate(45 60) rotate(15) scale(1.5)", $trans->translate(45));
        self::assertEquals("translate(30 45) rotate(15) scale(1.5)", $trans->translate(30, 45));
        self::assertEquals("translate(30 45) rotate(10) scale(1.5)", $trans->rotate(10));
        self::assertEquals("translate(30 45) rotate(10 60 60) scale(1.5)", $trans->rotate(10, 60));
        self::assertEquals("translate(30 45) rotate(10 45 30) scale(1.5)", $trans->rotate(null, 45, 30));
        self::assertEquals("translate(30 45) rotate(10 45 30) scale(1.5)", $trans->scale(null));
        self::assertEquals("translate(30 45) rotate(10 45 30) scale(2 1.5)", $trans->scale(2, 1.5));
        self::assertEquals("translate(30 45) rotate(10 45 30) scale(2 1.5)", $trans->scale(2, 1.5));
        self::assertEquals("translate(30 45) rotate(10 45 30) scale(2 1.5) matrix(1 1 1 0 0 0)", $trans->matrix([1, 1, 1, 0, 0, 0]));
        self::assertEquals("translate(15 45) rotate(10 45 30) scale(2 1.5) matrix(1 1 1 0 0 0)", $trans->translate(15));
    }

    public function invalidMatrixProvider()
    {
        return [
            '3 elements' => [
                ['matrix(1 1 1)']
            ],
            '7 elements' => [
                ['matrix(1 1 1 1 1 1 1)']
            ],
        ];
    }

    public function rotateProvider()
    {
        return [
            'empty' => [
                ['expected' => [null, null, null], 'transform' => 'rotate()']
            ],
            'only angle' => [
                ['expected' => ['-90.5', null, null], 'transform' => 'rotate(-90.5)']
            ],
            'integers' => [
                ['expected' => ['30', '30', '60'], 'transform' => 'rotate(30 30 60)']
            ],
            'negative integers' => [
                ['expected' => ['-30', '-30', '-60'], 'transform' => 'rotate(-30 -30 -60)']
            ],
            'spaces and floats' => [
                ['expected' => ['22.5', '30.132', '41.456'], 'transform' => 'rotate  (  22.5, 30.132,41.456   )    ']
            ],
            'negative integers and floats with comma' => [
                ['expected' => ['-30.58', '-30', '-60'], 'transform' => 'rotate(-30.58, -30, -60)']
            ],
        ];
    }
}
