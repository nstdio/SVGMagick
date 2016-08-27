<?php
namespace nstdio\svg\output;

/**
 * Interface ImageOutputInterface
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface ImageOutputInterface
{
    /**
     * @param string $content  The content of file.
     * @param string $name     The name of the file.
     * @param string $format   Format in which file will be encoded (jpeg, tiff, png ...). See [[IOFormat]].
     * @param bool   $override Whether the file should be overwritten.
     *
     * @return int The number of bytes that were written into the file
     */
    public function imageFile($content, $name, $format, $override);

    /**
     * @param string $content    The content of file.
     * @param string $format     Format in which file will be encoded (jpeg, tiff, png ...). See [[IOFormat]].
     * @param bool   $sendHeader Send "Content-Encoding", if the headers were not sent.
     *
     * @return string
     */
    public function image($content, $format, $sendHeader);
}