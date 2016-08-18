<?php

use nstdio\svg\animation\AnimateTransform;

class AnimateTransformTest extends SVGContextTestCase
{
    public function testAnimateTransform()
    {
        $attributes = [
            'attributeName' => 'transform',
            'attributeType' => 'XML',
            'type' => 'rotate',
            'from' => '0 250 150',
            'to' => '360 250 150',
            'dur' => '2s',
            'begin' => '0s',
            'fill' => 'freeze',
            'repeatCount' => 'indefinite',
            'restart' =>'whenNotActive',
        ];

        $animateTransform = new AnimateTransform($this->svgObj);
        $animateTransform->apply($attributes);

        $document = $this->getDocument();
        $animateTransformNode = $document->createElement('animateTransform');

        foreach ($attributes as $key => $value) {
            $animateTransformNode->setAttribute($key, $value);
        }

        $document->documentElement->appendChild($animateTransformNode);

        self::assertEqualXML($document);
    }
}
