<?php

use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\filter\ColorMatrix;
use nstdio\svg\gradient\UniformGradient;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;
use nstdio\svg\util\KeyValueWriter;

require_once __DIR__ . '/../vendor/autoload.php';

$saturate = isset($_GET['sat']) ? $_GET['sat'] : 40;
$hueRot = isset($_GET['rot']) ? $_GET['rot'] : 90;

$svg = new SVG('10cm', '5cm');
$svg->getElement()->setAttribute('viewBox', "0 0 500 500");

$defs = new Defs($svg);

$linearGradient = UniformGradient::uniformGradient($defs, ['#ff00ff', '#88ff88', '#2020ff', '#d00000'], null);
KeyValueWriter::apply($linearGradient->getElement(), ['gradientUnits' => 'userSpaceOnUse', 'x1' => 0, 'y1' => 0, 'x2' => $svg->getElement()->getAttribute('width'), 'y2' => 0,]);

$defs->append($linearGradient);

$g = new G($svg);
$g->fontFamily = 'Verdana';
$g->fontSize = 75;
$g->fontWeight = "bold";
$g->fill = "url(#$linearGradient->id)";

$rect = new Rect($svg, 20, 500, 0, 0);

$svg->append($g->append($rect));
$text = new Text($g, "Saturate" . $saturate);
$text->x = 0;
$text->y = 190;

$cMatrix = ColorMatrix::saturate($defs, $saturate);

$text->filter = "url(#$cMatrix->id)";
$g->append($text);

$hueRotate = ColorMatrix::hueRotate($defs, $hueRot);

$text2 = new Text($g, "HueRotate" . $hueRot);
$text2->x = 0;
$text2->y = 290;
$text2->filter = "url(#$hueRotate->id)";

$g->append($text2);

$lum = ColorMatrix::luminanceToAlphaWithComposite($defs);

$text3 = new Text($g, "Luminance");
$text3->x = 0;
$text3->y = 390;
$text3->filter = "url(#$lum->id)";

$g->append($text3);

$svg->append($defs);
echo $svg->draw();
