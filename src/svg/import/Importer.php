<?php
namespace nstdio\svg\import;

use nstdio\svg\container\SVG;
use nstdio\svg\ElementInterface;
use nstdio\svg\util\KeyValueWriter;
use ReflectionClass;

/**
 * Class Importer
 *
 * @package nstdio\svg\import
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Importer implements ImportInterface
{
    public static $map = [
        'animate'             => ['animation', ['attributeName', 'from', 'to', 'dur']],
        'mpath'               => ['animation', []],
        'animateMotion'       => ['animation', []],
        'set'                 => ['animation', []],
        'svg'                 => ['container', []],
        'a'                   => ['container', []],
        'defs'                => ['container', []],
        'g'                   => ['container', []],
        'marker'              => ['container', []],
        'mask'                => ['container', []],
        'pattern'             => ['container', []],
        'switch'              => ['container', []],
        'symbol'              => ['container', []],
        'desc'                => ['desc', ['nodeValue']],
        'metadata'            => ['desc', ['nodeValue']],
        'title'               => ['desc', ['nodeValue']],
        'filter'              => ['filter', []],
        'feBlend'             => ['filter', []],
        'feColorMatrix'       => ['filter', []],
        'feComponentTransfer' => ['filter', []],
        'feComposite'         => ['filter', []],
        'feConvolveMatrix'    => ['filter', []],
        'feDiffuseLighting'   => ['filter', []],
        'feDisplacementMap'   => ['filter', []],
        'feFlood'             => ['filter', []],
        'feFuncA'             => ['filter', []],
        'feFuncB'             => ['filter', []],
        'feFuncG'             => ['filter', []],
        'feFuncR'             => ['filter', []],
        'feGaussianBlur'      => ['filter', []],
        'image'               => ['filter', []],
        'feMerge'             => ['filter', []],
        'feMergeNode'         => ['filter', []],
        'feMorphology'        => ['filter', []],
        'feOffset'            => ['filter', []],
        'feSpecularLighting'  => ['filter', []],
        'feTile'              => ['filter', []],
        'feTurbulence'        => ['filter', []],
        'font'                => ['font', []],
        'font-face'           => ['font', []],
        'font-face-format'    => ['font', []],
        'font-face-name'      => ['font', []],
        'font-face-src'       => ['font', []],
        'font-face-uri'       => ['font', []],
        'glyph'               => ['font', []],
        'missing-glyph'       => ['font', []],
        'hkern'               => ['font', []],
        'vkern'               => ['font', []],
        'linearGradient'      => ['gradient', []],
        'radialGradient'      => ['gradient', []],
        'stop'                => ['gradient', []],
        'feDistantLight'      => ['light', []],
        'fePointLight'        => ['light', []],
        'feSpotLight'         => ['light', []],
        'circle'              => ['shape', ['cx', 'cy', 'r']],
        'ellipse'             => ['shape', ['cx', 'cy', 'rx', 'ry']],
        'line'                => ['shape', ['x1', 'y1', 'x2', 'y2']],
        'path'                => ['shape', ['x', 'y']],
        'polygon'             => ['shape', []],
        'polyline'            => ['shape', []],
        'rect'                => ['shape', ['height', 'width', 'x', 'y']],
        'altGlyph'            => ['text', []],
        'altGlyphDef'         => ['text', []],
        'altGlyphItem'        => ['text', []],
        'glyphRef'            => ['text', []],
        'text'                => ['text', ['nodeValue']],
        'textPath'            => ['text', []],
        'tref'                => ['text', []],
        'tspan'               => ['text', []],
    ];

    public function fromString($svgString)
    {
        $dom = new \DOMDocument('1.0', 'utf-8');

        $dom->loadXML($svgString);

        $svgNode = $dom->getElementsByTagName('svg')->item(0);
        if ($svgNode === null) {
            return null;
        }

        /** @var SVG $svg */
        $svg = $this->toObject($svgNode, null);
        $svg->removeChild($svg->getFirstChild());

        $this->buildObjectTree($svgNode, $svg);

        return $svg;
    }

    /**
     * @param \DOMElement $element
     *
     * @param             $parent
     *
     * @return ElementInterface
     */
    private function toObject($element, $parent)
    {
        $map = $this->getObjectCtor($element);
        if ($map === null) {
            return null;
        }
        $args = [$parent];

        $reflection = new ReflectionClass($map[0]);

        if (!empty($map[1])) {
            $args = array_merge($args, $this->getAttrs($element, $map[1]));
        }

        /** @var ElementInterface $object */
        $object = $reflection->newInstanceArgs($args);
        $object->apply(KeyValueWriter::allAttributes($element));

        return $object;
    }

    private function getObjectCtor(\DOMElement $element)
    {
        if (isset(self::$map[$element->nodeName]) === false) {
            return null;
        }
        $map = self::$map[$element->nodeName];
        $map[0] = 'nstdio\\svg\\' . $map[0] . '\\' . $element->nodeName;

        return $map;
    }

    /**
     * @param \DOMElement       $element
     *
     * @param             array $attrs
     *
     * @return array
     */
    private function getAttrs(\DOMElement $element, array $attrs)
    {
        $ret = [];
        foreach ($element->attributes as $item) {
            if (in_array($item->nodeName, $attrs)) {
                $ret[$item->nodeName] = $item->nodeValue;
            }
        }
        if (in_array('nodeValue', $attrs)) {
            $ret['nodeValue'] = $element->nodeValue;
        }

        $diff = array_diff($attrs, array_keys($ret));
        foreach ($diff as $item) {
            $ret[$item] = null;
        }

        return array_values($ret);
    }

    private function buildObjectTree(\DOMElement $element, ElementInterface $obj)
    {
        /** @var \DOMElement $item */
        foreach ($element->childNodes as $key => $item) {
            if ($item instanceof \DOMText) continue;
            $parent = $this->toObject($item, $obj);
            if ($item->hasChildNodes() && $parent !== null) {
                $this->buildObjectTree($item, $parent);
            }
        }
    }
}
