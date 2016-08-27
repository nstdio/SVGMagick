<?php
namespace nstdio\svg\output;

/**
 * interface SVGOutputInterface
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface SVGOutputInterface
{
    /**
     * Returns escaped svg as plain text.
     *
     * @return string
     */
    public function asString();

    /**
     * Returns not escaped svg.
     *
     * @return string
     */
    public function asSVG();

    /**
     * Returns Compressed SVG string
     *
     * @param bool $sendHeader Send "Content-Encoding", if the headers were not sent.
     *
     * @return string
     */
    public function asSVGZ($sendHeader = false);

    /**
     * @return string
     */
    public function asDataUriBase64();

    /**
     * Saves the svg file on the disk.
     *
     * @param string $name        The name of the file. If extension omitted it will be '$name.svg'.
     *                            In case of extension is .svgz file content will be gzip compressed.
     *
     * @param  bool  $prettyPrint Should the HTML structure be formatted.
     * @param  bool  $override    Whether the file should be overwritten.
     *
     * @return int The number of bytes that were written into the file
     */
    public function asFile($name, $prettyPrint, $override);

    /**
     * Saves image file on the disk.
     *
     * @param string $name     The name of the file.
     * @param string $format   Format in which file will be encoded (jpeg, tiff, png ...). See [[IOFormat]].
     * @param bool   $override Whether the file should be overwritten.
     *
     * @return int The number of bytes that were written into the file
     */
    public function asImageFile($name, $format, $override);

    /**
     * @param string $format     Format in which file will be encoded (jpeg, tiff, png ...). See [[IOFormat]].
     *
     * @param bool   $sendHeader Send "Content-Encoding", if the headers were not sent.
     *
     * @return string
     */
    public function asImage($format, $sendHeader);
}
