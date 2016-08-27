<?php
namespace nstdio\svg\output;

/**
 * Interface FileOutputInterface
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface FileOutputInterface
{
    /**
     * @param string $name     The file name.
     * @param string $content  The string to be written in file.
     * @param bool   $override Should override file with same name.
     *
     * @return int The number of bytes that were written into the file
     */
    public function file($name, $content, $override);
}