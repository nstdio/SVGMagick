<?php
use nstdio\svg\container\SVG;

require_once __DIR__ . '/../vendor/autoload.php';

$svgString = file_get_contents('output/Royal_Badge_of_Wales_(2008).svg');

$svg = SVG::fromString($svgString);
