<?php
namespace nstdio\svg\output;

/**
 * Interface FileOutputInterface
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface OutputInterface extends FileOutputInterface, ImageOutputInterface
{
    /**
     * @param FileOutputInterface $fileOutput
     *
     * @return void
     */
    public function setFileOutput(FileOutputInterface $fileOutput);

    /**
     * @param ImageOutputInterface $imageOutput
     *
     * @return void
     */
    public function setImageOutput(ImageOutputInterface $imageOutput);
}