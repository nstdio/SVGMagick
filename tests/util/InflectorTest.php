<?php

use nstdio\svg\util\Inflector;
use PHPUnit\Framework\TestCase;

class InflectorTest extends TestCase
{
    public function testCamel2Dash()
    {
        $this->assertEquals('stroke-width', Inflector::camel2dash('strokeWidth'));
        $this->assertEquals('stroke-width', Inflector::camel2dash('StrokeWidth'));
        $this->assertEquals('stroke', Inflector::camel2dash('Stroke'));
    }
}
