<?php
namespace nstdio\svg;

/**
 * interface ElementInterface
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface ElementInterface
{
    /**
     * @return \DOMElement
     */
    public function getRoot();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \DOMElement | ElementInterface
     */
    public function getElement();

    /**
     * @param array $assoc
     *
     * @return ElementInterface
     */
    public function apply(array $assoc);
}