<?php

use nstdio\svg\light\DistantLight;
use nstdio\svg\light\PointLight;
use nstdio\svg\light\SpotLight;

class BaseLightTest extends SVGContextTestCase
{
    public function testName()
    {
        $distant = new DistantLight($this->svgObj);
        $point = new PointLight($this->svgObj);
        $spot = new SpotLight($this->svgObj);

        self::assertEquals('feDistantLight', $distant->getName());
        self::assertEquals('fePointLight', $point->getName());
        self::assertEquals('feSpotLight', $spot->getName());
    }
}
