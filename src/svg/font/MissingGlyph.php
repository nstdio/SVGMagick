<?php
namespace nstdio\svg\font;

/**
 * Class MissingGlyph
 * The ‘missing-glyph’ element defines the graphics to use if there is an attempt to draw a glyph from a given font and
 * the given glyph has not been defined. The attributes on the ‘missing-glyph’ element have the same meaning as the
 * corresponding attributes on the ‘glyph’ element.
 *
 * @link    https://www.w3.org/TR/SVG11/fonts.html#MissingGlyphElement
 * @package nstdio\svg\font
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class MissingGlyph extends BaseFont
{

    public function getName()
    {
        return 'missing-glyph';
    }
}