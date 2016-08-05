<?php

use nstdio\svg\container\Defs;
use nstdio\svg\container\G;
use nstdio\svg\container\SVG;
use nstdio\svg\filter\ComponentTransfer;
use nstdio\svg\gradient\UniformGradient;
use nstdio\svg\shape\Rect;
use nstdio\svg\text\Text;
use nstdio\svg\util\KeyValueWriter;

require_once __DIR__ . '/../vendor/autoload.php';

$svg = new SVG('8cm', '4cm');
$svg->apply(['viewBox' => "0 0 800 400"]);

$defs = new Defs($svg);

$linearGradient = UniformGradient::uniformGradient($defs, ['#ff0000', '#00ff00', '#0000ff', '#000000'], null);
KeyValueWriter::apply($linearGradient->getElement(), ['gradientUnits' => 'userSpaceOnUse', 'x1' => 100, 'y1' => 0, 'x2' => 600, 'y2' => 0,]);

$g = new G($svg);
$g->apply(['fontFamily' => 'Verdana', 'fontSize' => 75, 'fontWeight' => 'bold', 'fill' => "url(#$linearGradient->id)"]);

$rect = new Rect($g, 20, 600, 100, 0);

$identity = ComponentTransfer::identity($defs);
$text = new Text($g, "Identity");
$text->apply(['x' => 100, 'y' => 90, 'filter' => "url(#$identity->id)"]);

$table = ComponentTransfer::table($defs, [[0, 0, 1, 1], [1, 1, 0, 0], [0, 1, 1, 0]]);
$text2 = new Text($g, "TableLookup");
$text2->apply(['x' => 100, 'y' => 190, 'filter' => "url(#$table->id)"]);

$linear = ComponentTransfer::linear($defs, 0.5, [0.25, 0, 0.5]);
$text3 = new Text($g, "LinearFunc");
$text3->apply(['x' => 100, 'y' => 290, 'filter' => "url(#$linear->id)"]);

$gamma = ComponentTransfer::gamma($defs, 2, [5, 3, 1], 0);
$text4 = new Text($g, "GammaFunc");
$text4->apply(['x' => 100, 'y' => 390, 'filter' => "url(#$gamma->id)"]);

echo $svg->draw();
