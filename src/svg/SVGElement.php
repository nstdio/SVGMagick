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
 * The base class for all elements.
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

    /**
     * This attribute will not be converted.
     *
     * @see SVGElement::__get;
     * @var array
     */
    private static $notConvertable = ['patternContentUnits', 'patternTransform', 'patternUnits', 'diffuseConstant', 'pointsAtX', 'pointsAtY', 'pointsAtZ', 'limitingConeAngle', 'tableValues', 'filterUnits', 'gradientUnits', 'viewBox', 'repeatCount', 'attributeName', 'attributeType', 'stdDeviation'];

    /**
     * The parent of `$element`.
     *
     * @var XMLDocumentInterface | ElementFactoryInterface | ContainerInterface
     */
    protected $root;

    /**
     * The element itself.
     *
     * @var XMLDocumentInterface | ElementFactoryInterface | ContainerInterface
     */
    protected $element;

    public function __construct(ElementInterface $parent)
    {
        $this->child = new ElementStorage();
        $this->root = $parent;
        $this->element = $this->createElement($this->getName());
        $this->root->append($this);
    }

    /**
     * @inheritdoc
     */
    public function createElement($name, $value = null, $attributes = [])
    {
        return $this->root->createElement($name, $value, $attributes);
    }

    /**
     * @inheritdoc
     */
    abstract public function getName();

    /**
     * @inheritdoc
     */
    final public function getRoot()
    {
        return $this->root;
    }

    /**
     * @inheritdoc
     */
    final public function getElement()
    {
        return $this->element;
    }

    /**
     * @param string[] $except
     *
     * @see XMLDocumentInterface::attributes()
     * @return array
     */
    public function allAttributes(array $except = [])
    {
        return $this->element->attributes($except);
    }

    /**
     * In order to have short access to the attributes of an element. Any attribute can be obtained as a public
     * property object. Attributes that are written with a hyphen can be obtained through a simple conversion.
     *
     * ```php
     * // ...
     *
     * echo $circle->strokeWidth; // Property strokeWidth will be transformed into stroke-width and so on.
     * echo $blur->stdDeviation; // Property stdDeviation will not converted into std-deviation.
     * ```
     *
     * For properties `$fill` and `$filter` will be returned something like this `url(#idTheRefToElement)`. In order to
     * have direct access to `idTheRefToElement` part,
     * ```php
     * // ...
     * $circle->filter = "url(#someFilterId)";
     * $id = $circle->filterUrl;
     * echo $id; // will print someFilterId and same story with fillUrl
     * ```
     * You can also have access to attributes with `xlink` namespace as mentioned above.
     * For example you need to get `xlink:href` value. If given element doest not have `href` attribute (with no
     * namespace prefix) it will try to get `xlink:href`.
     * ```php
     * $id = $mPath->href;
     * echo $id; // will print #someHref.
     * ```
     *
     * @param string $name The name of property.
     *
     * @see SVGElement::$notConvertable
     * @return null|string
     */
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

    /**
     * Has same propose as `__get()` and same `$filterUrl`, `$fillUrl`, name converting policy.
     * You can generate an id for the element by assigning null.
     * ```php
     * // ...
     * $circle->id = null;
     * echo $circle->id; // will print something like this __circle12345.
     * ```
     *
     * @param string $name  The name of property.
     * @param mixed  $value The value of property.
     */
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

    /**
     * @param string $name The local name of `xlink` namespaced attribute.
     *
     * @return string The value of attribute.
     */
    public function getXLinkAttribute($name)
    {
        return $this->element->getAttributeNS('xlink', $name);
    }

    /**
     * @param string $name  The local name of `xlink` attribute.
     * @param mixed  $value The value of attribute.
     */
    public function setXLinkAttribute($name, $value)
    {
        $this->element->setAttributeNS('xlink', "xlink:$name", $value);
    }

    /**
     * If attribute name is convertable converts it from camelCase to dashed.
     *
     * @param string $name The string to convert.
     *
     * @return string The converted string.
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

    /**
     * It removes the object itself from the DOM and from the list of the children of its parent.
     */
    protected function selfRemove()
    {
        $this->getRoot()->removeChild($this);
    }

    /**
     * @return SVG The root element of hierarchy.
     */
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

    /**
     * Returns standard `defs` element for `svg`.
     *
     * @param ElementInterface $container Where to search `defs` element.
     *
     * @return Defs The `defs` element.
     */
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
     * Places the `$value` into `url(#$value)` if `url(#` is not present.
     *
     * @param string $attribute The `$filterUrl` or `$fillUrl` properties.
     * @param string $value     The wrapped string.
     */
    private function handleUrlPostfixAttribute(&$attribute, &$value)
    {
        $attribute = substr($attribute, 0, strrpos($attribute, 'U'));
        if (strpos($value, "url(#") !== 0) {
            $value = "url(#" . $value . ")";
        }
    }

    /**
     * Retrieves the id from the `url(#id)` string.
     *
     * @param string $attribute The `$filterUrl` or `$fillUrl` properties.
     *
     * @return string The extracted string.
     */
    private function getIdFromUrl($attribute)
    {
        $attribute = substr($attribute, 0, strrpos($attribute, 'U'));
        return str_replace(['url(#', ')'], '', $this->element->getAttribute($attribute));
    }
}