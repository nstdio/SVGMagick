<?php

use nstdio\svg\container\Defs;
use nstdio\svg\import\Importer;

class ImporterTest extends SVGContextTestCase
{
    /**
     * @var Importer
     */
    private $importer;

    public function setUp()
    {
        parent::setUp();
        $this->importer = new Importer();
    }

    public function testNoSvgString()
    {
        $actual = $this->importer->fromString("<html><body><p>There is no svg inside.</p></body></html>");

        self::assertNull($actual);
    }

    public function testEmptyImport()
    {
        $svg = $this->importer->fromString($this->svgObj->draw());

        self::assertEquals($svg, $this->svgObj);
    }

    public function testImport()
    {
        $svgString = <<<SVG
<?xml version="1.0" encoding="utf-8" standalone="no"?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="640" height="480" version="1.1" viewBox="0 0 640 480" style="border: 1px solid blue;">
  <desc>This is for nodeValue test</desc>
  <p>HTML paragraph</p>
  <defs>
    <pattern id="__pattern54747" x="0" y="0" height="10" width="10" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
      <line x1="0" y1="5" x2="10" y2="5" stroke="black" stroke-width="1" fill="none"/>
      <line x1="5" y1="0" x2="5" y2="10" id="__line57903" stroke="black" stroke-width="1" fill="none"/>
    </pattern>
    <pattern id="__pattern29889" x="0" y="0" height="20" width="20" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
      <line x1="0" y1="10" x2="20" y2="10" stroke="red" stroke-width="0.5" fill="none" stroke-dasharray="1 1"/>
      <line x1="10" y1="0" x2="10" y2="20" id="__line84526" stroke="blue" stroke-width="0.5" fill="none" stroke-dasharray="1 1"/>
    </pattern>
    <pattern id="__pattern46581" x="0" y="0" height="4" width="10" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
      <line x1="0" y1="0" x2="0" y2="4" stroke="orangered" stroke-width="0.5" fill="none" stroke-dasharray="1 1"/>
    </pattern>
    <pattern id="__pattern84913" x="0" y="0" height="4" width="2" patternUnits="userSpaceOnUse">
      <line x1="0" y1="0" x2="0" y2="4" stroke="darkred" stroke-width="1" fill="none"/>
    </pattern>
    <linearGradient id="__gradient8996" x1="0%" y1="100%" x2="0%" y2="0%">
      <stop offset="0" stop-color="red"/>
      <stop offset="0.25" stop-color="green"/>
      <stop offset="0.5" stop-color="blue"/>
      <stop offset="0.75" stop-color="orange"/>
      <stop offset="1" stop-color="darkred"/>
    </linearGradient>
    <pattern id="__pattern23356" x="0" y="0" height="16" width="16" patternUnits="userSpaceOnUse">
      <circle cx="8" cy="8" r="8" fill="url(#__gradient8996)"/>
      <rect height="7" width="7" x="0.5" y="0.5" fill="black" fill-opacity="0.5" stroke="gray" stroke-width="0.5"/>
      <rect id="__rect17825" height="7" width="7" x="7.5" y="7.5" fill="black" fill-opacity="0.5" stroke="gray" stroke-width="0.5"/>
    </pattern>
  </defs>
  <g stroke="green" stroke-width="0.5">
    <circle cx="52" cy="52" r="50" fill="url(#__pattern54747)"/>
    <rect width="120" x="110" y="2" fill="url(#__pattern29889)"/>
    <circle cx="295" cy="52" r="50" fill="url(#__pattern46581)"/>
    <ellipse cx="420" cy="50" rx="70" ry="30" fill="url(#__pattern84913)"/>
    <circle cx="52" cy="160" r="50" fill="url(#__pattern23356)"/>
  </g>
</svg>
SVG;

        $svg = $this->importer->fromString($svgString);

        self::assertAttributeCount(3, 'child', $svg);

        $desc = $svg->getFirstChild();
        self::assertEquals("This is for nodeValue test", $desc->getElement()->getNodeValue());

        /** @var Defs $defs */
        $defs = $svg->getChildAtIndex(1);
        self::assertAttributeCount(6, 'child', $defs);

        self::assertCount(5, $defs->getChild('pattern'));
        self::assertCount(1, $defs->getChild('linearGradient'));

        $defsAssert = [
            [
                [
                    'id'               => '__pattern54747',
                    'x'                => '0',
                    'y'                => '0',
                    'height'           => '10',
                    'width'            => '10',
                    'patternUnits'     => 'userSpaceOnUse',
                    'patternTransform' => 'rotate(45)',
                ],
                "pattern",
                2,
            ],
            [
                [
                    'id'               => '__pattern29889',
                    'x'                => '0',
                    'y'                => '0',
                    'height'           => '20',
                    'width'            => '20',
                    'patternUnits'     => 'userSpaceOnUse',
                    'patternTransform' => 'rotate(45)',
                ],
                'pattern',
                2,
            ],
            [
                [
                    'id'               => '__pattern46581',
                    'x'                => '0',
                    'y'                => '0',
                    'height'           => '4',
                    'width'            => '10',
                    'patternUnits'     => 'userSpaceOnUse',
                    'patternTransform' => 'rotate(45)',
                ],
                'pattern',
                1,
            ],
            [
                [
                    'id'           => '__pattern84913',
                    'x'            => '0',
                    'y'            => '0',
                    'height'       => '4',
                    'width'        => '2',
                    'patternUnits' => 'userSpaceOnUse',
                ],
                'pattern',
                1,
            ],
            [
                [
                    'id' => '__gradient8996',
                    'x1' => '0%',
                    'y1' => '100%',
                    'x2' => '0%',
                    'y2' => '0%',
                ],
                'linearGradient',
                5,
            ],
            [
                [
                    'id'           => '__pattern23356',
                    'x'            => '0',
                    'y'            => '0',
                    'height'       => '16',
                    'width'        => '16',
                    'patternUnits' => 'userSpaceOnUse',
                ],
                'pattern',
                3,
            ],
        ];

        foreach ($defsAssert as $key => $value) {
            $child = $defs->getChildAtIndex($key);
            self::assertEquals($value[0], $child->allAttributes());
            self::assertEquals($value[1], $child->getName());
            self::assertAttributeCount($value[2], 'child', $child);
        }
    }
}
