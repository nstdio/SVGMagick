<?php
namespace nstdio\svg\font;

/**
 * Class FontFaceSrc
 * The ‘font-face-src’ element, together with the ‘font-face-uri’ and ‘font-face-format’ elements described in the
 * following sections, correspond to the ‘src’ descriptor within an @font-face rule. (Refer to the descriptions of the
 * @font-face rule and 'src' descriptor in the CSS2 specification ([CSS2], sections 15.3.1 and 15.3.5).
 *
 * A ‘font-face-src’ element contains ‘font-face-uri’ and ‘font-face-name’ elements, which are used for referencing
 * external and local fonts, respectively.
 *
 * @package nstdio\svg\font
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class FontFaceSrc extends BaseFont
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "font-face-src";
    }

}