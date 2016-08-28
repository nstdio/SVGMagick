<?php
namespace nstdio\svg;

use Doctrine\Instantiator\Instantiator;
use nstdio\svg\attributes\Transformable;
use nstdio\svg\container\ContainerInterface;
use nstdio\svg\container\Defs;
use nstdio\svg\container\SVG;
use nstdio\svg\traits\ChildTrait;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\util\Identifier;
use nstdio\svg\util\Inflector;
use nstdio\svg\util\KeyValueWriter;
use nstdio\svg\util\Transform;
use nstdio\svg\util\TransformInterface;

/**
 * Class SVGElement
 *
 * @property string $id
 * @property string $fill The fill color.
 * @property float $height The height attribute of element.
 * @property float $width The width attribute of element.
 *
 * @package nstdio\svg
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class SVGElement implements ContainerInterface, ElementFactoryInterface
{
    use ElementTrait, ChildTrait;

    private static $notConvertable = ['patternContentUnits', 'patternTransform', 'patternUnits', 'diffuseConstant', 'pointsAtX', 'pointsAtY', 'pointsAtZ', 'limitingConeAngle', 'tableValues', 'filterUnits', 'gradientUnits', 'viewBox', 'repeatCount', 'attributeName', 'attributeType', 'stdDeviation'];

    /**
     * @var XMLDocumentInterface | ElementInterface | ElementFactoryInterface | ContainerInterface
     */
    protected $root;

    /**
     * @var XMLDocumentInterface | ElementInterface | ElementFactoryInterface | ContainerInterface
     */
    protected $element;

    public function __construct(ElementInterface $parent)
    {
        $this->child = new ElementStorage();
        $this->root = $parent;
        $this->element = $this->createElement($this->getName());
        $this->root->append($this);
    }

    public function createElement($name, $value = null, $attributes = [])
    {
        return $this->root->createElement($name, $value, $attributes);
    }

    abstract public function getName();

    final public function getRoot()
    {
        return $this->root;
    }

    final public function getElement()
    {
        return $this->element;
    }

    public function allAttributes(array $except = [])
    {
        return $this->element->attributes($except);
    }

    public function __get($name)
    {
        if ($name === 'filterUrl' || $name === 'fillUrl') {
            return $this->getIdFromUrl($name);
        }

        $name = $this->convertAttributeName($name);
        $value = $this->element->getAttribute($name);
        if ($value === '') {
            $value = $this->getXLinkAttribute($name);
        }

        return $value === '' ? null : $value;
    }

    public function __set($name, $value)
    {
        if ($name === 'id' && $value === null) {
            $this->element->setAttribute($name, Identifier::random('__' . $this->getName(), 5));

            return;
        }

        if ($value === null || $value === false || $value === '') {
            return;
        }

        if ($name === 'filterUrl' || $name === 'fillUrl') {
            $this->handleUrlPostfixAttribute($name, $value);
        }

        $name = $this->convertAttributeName($name);
        $this->element->setAttribute($name, $value);
    }

    public function getXLinkAttribute($name)
    {
        return $this->element->getAttributeNS('xlink', $name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function convertAttributeName($name)
    {
        return !in_array($name, self::$notConvertable) ? Inflector::camel2dash($name) : $name;
    }

    /**
     * @inheritdoc
     */
    public function apply(array $assoc)
    {
        $filtered = [];
        foreach ($assoc as $attribute => $value) {
            $filtered[$this->convertAttributeName($attribute)] = $value;
        }
        KeyValueWriter::apply($this->element, $filtered);

        return $this;
    }

    protected function selfRemove()
    {
        $this->getRoot()->removeChild($this);
    }

    protected function getSVG()
    {
        if ($this->root instanceof SVG) {
            return $this->root;
        }
        $element = $this->root;

        do {
            $element = $element->getRoot();
        } while (!($element instanceof SVG));

        return $element;
    }

    protected static function getDefs(ElementInterface $container)
    {
        if ($container instanceof Defs) {
            return $container;
        }

        if ($container instanceof SVG) {
            $defs = $container->getFirstChild();
        } else {
            /** @var SVGElement $container */
            $defs = $container->getSVG()->getFirstChild();
        }

        return $defs;
    }

    /**
     * @inheritdoc
     */
    public function copy(array $apply = [], array $ignore = [], ContainerInterface $parent = null)
    {
        /** @var SVGElement $instance */
        $instance = (new Instantiator())->instantiate(get_class($this));
        $instance->root = $parent === null ? $this->getRoot() : $parent;
        $instance->child = new ElementStorage();
        $instance->element = $this->createElement($this->getName());
        $instance->id = null;

        if ($instance instanceof TransformInterface && $this instanceof Transformable) {
            $instance->transformImpl = Transform::newInstance($this->getTransformAttribute());
        }
        $ignore[] = 'id';
        $apply = array_merge($this->allAttributes($ignore), $apply);
        $instance->apply($apply);
        $parent === null ? $this->root->append($instance) : $parent->append($instance);

        return $instance;
    }

    /**
     * @param $attribute
     * @param $value
     */
    private function handleUrlPostfixAttribute(&$attribute, &$value)
    {
        $attribute = substr($attribute, 0, strrpos($attribute, 'U'));
        if (strpos($value, "url(#") !== 0) {
            $value = "url(#" . $value . ")";
        }
    }

    private function getIdFromUrl($attribute)
    {
        $attribute = substr($attribute, 0, strrpos($attribute, 'U'));
        return str_replace(['url(#', ')'], '', $this->element->getAttribute($attribute));
    }
}