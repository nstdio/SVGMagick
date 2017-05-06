<?php

use nstdio\svg\util\transform\Transform;
use nstdio\svg\util\transform\TransformInterface;

class TransformTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid delimiter. See TransformInterface::setArgumentDelimiter documentation for
     *                           valid value.
     */
    public function testDelimException()
    {
        $trans = Transform::newInstance();

        $trans->setArgumentDelimiter('invalid_delimiter');
    }

    public function testArgumentDelimiter()
    {
        $trans = Transform::newInstance();
        $trans->setArgumentDelimiter(TransformInterface::TRANSFORM_ARG_DELIM_COMMA);

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

        self::assertEquals($data['expected'], $trans->getData()[0]['rotate']);
    }

    public function testInitialRotate()
    {
        $trans = Transform::newInstance();
        $angle = 45;

        $input = "rotate($angle)";
        self::assertEquals($input, $trans->rotate($angle));
        $input .= " rotate(75.25)";
        self::assertEquals($input, $trans->rotate(75.25));
        $input .= " rotate($angle 0 0)";
        self::assertEquals($input, $trans->rotate($angle, 0));
        $input .= " rotate(0 0 1)";
        self::assertEquals($input, $trans->rotate(null, 0, 1));
        $input .= " rotate($angle 1 1)";
        self::assertEquals($input, $trans->rotate($angle, null, 1));
    }

    public function testTranslate()
    {
        $trans = Transform::newInstance();

        $input = "translate(40)";
        self::assertEquals($input, $trans->translate(40));
        $input .= " translate(40.5)";
        self::assertEquals($input, $trans->translate(40.5));
        $input .= " translate(40 5)";
        self::assertEquals($input, $trans->translate(40, 5));
    }

    public function testScale()
    {
        $trans = Transform::newInstance();

        self::assertEquals("scale(1.5)", $trans->scale(1.5));
        self::assertEquals("scale(1.5) scale(0.5 0.8)", $trans->scale(0.5, 0.8));

        $trans = Transform::newInstance("scale(2)");

        self::assertEquals("scale(2)", $trans->result());
        self::assertEquals("scale(2) scale(0.5)", $trans->scale(0.5));
    }

    public function testSkew()
    {
        $trans = Transform::newInstance("skewX(0.2) skewY(1)");

        self::assertEquals("skewX(0.2) skewY(1)", $trans->result());
        self::assertEquals("skewX(0.2) skewY(1) skewX(1.5)", $trans->skewX(1.5));
        self::assertEquals("skewX(0.2) skewY(1) skewX(1.5) skewX(20)", $trans->skewX(20));
        self::assertEquals("skewX(0.2) skewY(1) skewX(1.5) skewX(20) skewY(20)", $trans->skewY(20));

        $trans->skewY(1.8);
        $trans->skewX(1.8);

        self::assertEquals("skewX(0.2) skewY(1) skewX(1.5) skewX(20) skewY(20) skewY(1.8) skewX(1.8)", $trans->result());
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
        self::assertEquals("matrix(1 1 1 0 0 0) matrix(1 1 1 1 1 1)", $trans->matrix([1, 1, 1, 1, 1, 1]));
    }

    public function testTransform()
    {
        $input = "translate(30 60) rotate(15) scale(1.5)";
        $trans = Transform::newInstance($input);

        $input .= " translate(45)";
        self::assertEquals($input, $trans->translate(45));
        $input .= " translate(30 45)";
        self::assertEquals($input, $trans->translate(30, 45));
        $input .= " rotate(10)";
        self::assertEquals($input, $trans->rotate(10));
        $input .= " rotate(10 60 60)";
        self::assertEquals($input, $trans->rotate(10, 60));
        $input .= " rotate(0 45 30)";
        self::assertEquals($input, $trans->rotate(null, 45, 30));
        $input .= " scale(0)";
        self::assertEquals($input, $trans->scale(null));
        $input .= " scale(2 1.5)";
        self::assertEquals($input, $trans->scale(2, 1.5));
        $input .= " scale(2 1.5)";
        self::assertEquals($input, $trans->scale(2, 1.5));
        $input .= " matrix(1 1 1 0 0 0)";
        self::assertEquals($input, $trans->matrix([1, 1, 1, 0, 0, 0]));
        $input .= " translate(15)";
        self::assertEquals($input, $trans->translate(15));
    }

    public function testTransformAdd()
    {
        $trans = Transform::newInstance();

        self::assertEquals("translate(45)", $trans->translate(45));
        self::assertEquals("translate(45) rotate(10)", $trans->rotate(10));
        self::assertEquals("translate(45) rotate(10) translate(60 40)", $trans->translate(60, 40));
    }

    /**
     * @dataProvider rotateInvalidArgsProvider
     *
     * @param $data
     */
    public function testRotateInvalidArgs($data)
    {
        $trans = Transform::newInstance();

        self::assertArrayHasKey('args', $data);
        self::assertArrayHasKey('expected', $data);
        self::assertCount(3, $data['args']);

        $args = $data['args'];

        $trans->rotate($args[0], $args[1], $args[2]);

        self::assertEquals($data['expected'], $trans->result());
    }

    public function invalidMatrixProvider()
    {
        return [
            '3 elements' => [
                ['matrix(1 1 1)'],
            ],
            '7 elements' => [
                ['matrix(1 1 1 1 1 1 1)'],
            ],
        ];
    }

    public function rotateInvalidArgsProvider()
    {
        return [
            'angle null'                   => [
                [
                    'args' => [null, 20, 20], 'expected' => 'rotate(0 20 20)',
                ],
            ],
            'angle empty string'           => [
                [
                    'args' => ['', 20, 20], 'expected' => 'rotate(0 20 20)',
                ],
            ],
            'angle not convertible string' => [
                [
                    'args' => ['asd', 20, 20], 'expected' => 'rotate(0 20 20)',
                ],
            ],
            'all args are invalid'         => [
                [
                    'args' => ['asd', 'w', 'R'], 'expected' => 'rotate(0 0 0)',
                ],
            ],
        ];
    }

    public function rotateProvider()
    {
        return [
            'empty'                                   => [
                ['expected' => [null, null, null], 'transform' => 'rotate()'],
            ],
            'only angle'                              => [
                ['expected' => ['-90.5', null, null], 'transform' => 'rotate(-90.5)'],
            ],
            'integers'                                => [
                ['expected' => ['30', '30', '60'], 'transform' => 'rotate(30 30 60)'],
            ],
            'negative integers'                       => [
                ['expected' => ['-30', '-30', '-60'], 'transform' => 'rotate(-30 -30 -60)'],
            ],
            'spaces and floats'                       => [
                ['expected' => ['22.5', '30.132', '41.456'], 'transform' => 'rotate  (  22.5, 30.132,41.456   )    '],
            ],
            'negative integers and floats with comma' => [
                ['expected' => ['-30.58', '-30', '-60'], 'transform' => 'rotate(-30.58, -30, -60)'],
            ],
        ];
    }
}
