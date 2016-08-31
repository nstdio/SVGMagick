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
    /**
     * Creates new element.
     *
     * @param string $name       The name of created element.
     * @param null   $value      Text content of element.
     * @param array  $attributes The key => value array. As keys user must pass attribute names. Note that camelCase
     *                           attribute will be not converted into dash-separated.
     *
     * @see XMLDocumentInterface::createElement()
     * @return XMLDocumentInterface
     */
    public function createElement($name, $value = null, $attributes = []);
}