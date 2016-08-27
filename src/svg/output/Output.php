<?php
namespace nstdio\svg\output;

/**
 * Class Output
 *
 * @package nstdio\svg\output
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Output implements OutputInterface
{
    /**
     * @var FileOutputInterface
     */
    private $fileImpl;

    /**
     * @var ImageOutputInterface
     */
    private $imageImpl;

    public function __construct(FileOutputInterface $fileOutput = null, ImageOutputInterface $imageOutput = null)
    {
        $this->fileImpl = $fileOutput === null ? new FileOutput() : $fileOutput;
        $this->imageImpl = $imageOutput === null ? new ImageOutput() : $imageOutput;
    }

    /**
     * @inheritdoc
     */
    public function file($name, $content, $override)
    {
        return $this->fileImpl->file($name, $content, $override);
    }

    /**
     * @inheritdoc
     */
    public function imageFile($content, $name, $format, $override)
    {
        return $this->imageImpl->imageFile($content, $name, $format, $override);
    }

    /**
     * @inheritdoc
     */
    public function image($content, $format, $sendHeader)
    {
        return $this->imageImpl->image($content, $format, $sendHeader);
    }

    /**
     * @inheritdoc
     */
    public function setFileOutput(FileOutputInterface $fileOutput)
    {
        $this->fileImpl = $fileOutput;
    }

    /**
     * @inheritdoc
     */
    public function setImageOutput(ImageOutputInterface $imageOutput)
    {
        $this->imageImpl = $imageOutput;
    }
}