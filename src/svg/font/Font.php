<?php
namespace nstdio\svg\font;

/**
 * Class Font
 * The ‘font’ element defines an SVG font. Each ‘font’ element must have a ‘font-face’ child element which describes
 * various characteristics of the font.
 *
 * @link    https://www.w3.org/TR/SVG11/fonts.html#FontElement
 * @property float horiz        -origin-x = "<number>" The X-coordinate in the font coordinate system of the origin of
 *           a glyph to be used when drawing horizontally oriented text. (Note that the origin applies to all glyphs in
 *           the font.) If the attribute is not specified, the effect is as if a value of '0' were specified.
 * @property float horizOriginY = "<number>" The Y-coordinate in the font coordinate system of the origin of a glyph
 *           to be used when drawing horizontally oriented text. (Note that the origin applies to all glyphs in the
 *           font.) If the attribute is not specified, the effect is as if a value of '0' were specified.
 * @property float horizAdvX    = "<number>" The default horizontal advance after rendering a glyph in horizontal
 *           orientation. Glyph widths are required to be non-negative, even if the glyph is typically rendered
 *           right-to-left, as in Hebrew and Arabic scripts.
 * @property float vertOriginX  = "<number>" The default X-coordinate in the font coordinate system of the origin of
 *           a glyph to be used when drawing vertically oriented text. If the attribute is not specified, the effect is
 *           as if the attribute were set to half of the effective value of attribute ‘horiz-adv-x’.
 * @property float vertOriginY  = "<number>" The default Y-coordinate in the font coordinate system of the origin of
 *           a glyph to be used when drawing vertically oriented text. If the attribute is not specified, the effect is
 *           as if the attribute were set to the position specified by the font's ‘ascent’ attribute.
 * @property float vertAdvY     = "<number>" The default vertical advance after rendering a glyph in vertical
 *           orientation. If the attribute is not specified, the effect is as if a value equivalent of one em were
 *           specified (see ‘units-per-em’).
 * @package nstdio\svg\font
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Font extends BaseFont
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "font";
    }

}