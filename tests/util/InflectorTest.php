<?php

use nstdio\svg\util\Inflector;

class InflectorTest extends PHPUnit_Framework_TestCase
{
    public function testCamel2Dash()
    {
        $this->assertEquals('stroke-width', Inflector::camel2dash('strokeWidth'));
        $this->assertEquals('stroke-width', Inflector::camel2dash('StrokeWidth'));
        $this->assertEquals('stroke', Inflector::camel2dash('Stroke'));
    }
}
