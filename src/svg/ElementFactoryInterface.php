<?php
namespace nstdio\svg;

/**
 * interface ElementFactoryInterface
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface ElementFactoryInterface
{
    public function createElement($name, $value = null, $attributes = []);
}