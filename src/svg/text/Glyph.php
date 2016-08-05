<?php
namespace nstdio\svg\text;

/**
 * Class Glyph
 * The ‘glyph’ element defines the graphics for a given glyph. The coordinate system for the glyph is defined by the
 * various attributes in the ‘font’ element.
 * The graphics that make up the ‘glyph’ can be a single path data specification within the ‘d’ attribute, arbitrary
 * SVG as content within the ‘glyph’, or both. These two alternatives are processed differently.
 *
 * @link    https://www.w3.org/TR/SVG11/fonts.html#GlyphElement
 * @property string unicode     = "<string>" One or more Unicode characters indicating the sequence of Unicode
 *           characters which corresponds to this glyph. If a character is provided, then this glyph corresponds to the
 *           given Unicode character. If multiple characters are provided, then this glyph corresponds to the given
 *           sequence of Unicode characters. One use of a sequence of characters is ligatures. For example, if
 *           unicode="ffl", then the given glyph will be used to render the sequence of characters "f", "f", and "l".
 *           It is often useful to refer to characters using XML character references expressed in hexadecimal notation
 *           or decimal notation. For example, unicode="ffl" could be expressed as XML character references in
 *           hexadecimal notation as unicode="&#x66;&#x66;&#x6c;" or in decimal notation as
 *           unicode="&#102;&#102;&#108;". The ‘unicode’ attribute contributes to the process for deciding which
 *           glyph(s) are used to represent which character(s). See glyph selection rules. If the
 *          ‘unicode’ attribute is not provided for a given ‘glyph’, then the only way to use this glyph is via an
 *          ‘altGlyph’ reference.
 * @property string glyphName   = "<name> [, <name> ]* " A name for the glyph. It is recommended that glyph names
 *           be unique within a font. The glyph names can be used in situations where Unicode character numbers do not
 *           provide sufficient information to access the correct glyph, such as when there are multiple glyphs per
 *           Unicode character. The glyph names can be referenced in kerning definitions.
 * @property string d           = "path data" The definition of the outline of a glyph, using the same syntax as for
 *           the
 *           ‘d’ attribute on a ‘path’ element. See Path data. See below for a discussion of this attribute.
 * @property string orientation = "h | v" Indicates that the given glyph is only to be used for a particular
 *           inline-progression-direction (i.e., horizontal or vertical). If the attribute is not specified, then the
 *           glyph can be used in all cases (i.e., both horizontal and vertical inline-progression-direction).
 * @property string arabicForm  = "initial | medial | terminal | isolated" For Arabic glyphs, indicates which of
 *           the four possible forms this glyph represents.
 * @property string lang        = "%LanguageCodes;" The attribute value is a comma-separated list of language names as
 *           defined in BCP 47 {@link http://www.ietf.org/rfc/bcp/bcp47.txt}. The glyph can be used if the ‘xml:lang’
 *           attribute exactly matches one of the languages given in the value of this parameter, or if the ‘xml:lang’
 *           attribute exactly equals a prefix of one of the languages given in the value of this parameter such that
 *           the first tag character following the prefix is "-".
 * @property float  horizAdvX   = "<number>" The horizontal advance after rendering the glyph in horizontal
 *           orientation. If the attribute is not specified, the effect is as if the attribute were set to the value of
 *           the font's ‘horiz-adv-x’ attribute. Glyph widths are required to be non-negative, even if the glyph is
 *           typically rendered right-to-left, as in Hebrew and Arabic scripts.
 * @property float  vertOriginX = "<number>" The X-coordinate in the font coordinate system of the origin of
 *           the glyph to be used when drawing vertically oriented text. If the attribute is not specified, the effect
 *           is as if the attribute were set to the value of the font's ‘vert-origin-x’ attribute.
 * @property float  vertOriginY = "<number>" The Y-coordinate in the font coordinate system of the origin of a glyph to
 *           be used when drawing vertically oriented text. If the attribute is not specified, the effect is as if the
 *           attribute were set to the value of the font's ‘vert-origin-y’ attribute.
 * @property float  vertAdvY    = "<number>" The vertical advance after rendering a glyph in vertical orientation. If
 *           the attribute is not specified, the effect is as if the attribute were set to the value of the font's
 *           ‘vert-adv-y’ attribute.
 * @package nstdio\svg\text
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Glyph extends BaseText
{

    public function getName()
    {
        return 'glyph';
    }
}