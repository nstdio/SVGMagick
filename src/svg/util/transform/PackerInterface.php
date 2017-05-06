<?php
namespace nstdio\svg\util\transform;

/**
 * Interface PackerInterface
 *
 * @package nstdio\svg\util\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface PackerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function pack(array $data);

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function toMatrix(array $data);
}