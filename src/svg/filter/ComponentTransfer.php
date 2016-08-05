<?php
namespace nstdio\svg\filter;

use nstdio\svg\container\ContainerInterface;
use nstdio\svg\traits\ElementTrait;
use nstdio\svg\util\KeyValueWriter;

/**
 * Class ComponentTransfer
 * This filter primitive performs component-wise remapping of data as follows:
 *
 * R' = feFuncR( R )
 * G' = feFuncG( G )
 * B' = feFuncB( B )
 * A' = feFuncA( A )
 * for every pixel. It allows operations like brightness adjustment, contrast adjustment, color balance or
 * thresholding.
 *
 * The calculations are performed on non-premultiplied color values. If the input graphics consists of premultiplied
 * color values, those values are automatically converted into non-premultiplied color values for this operation. (Note
 * that the undoing and redoing of the premultiplication can be avoided if feFuncA is the identity transform and all
 * alpha values on the source graphic are set to 1.)
 *
 * @link    https://www.w3.org/TR/SVG/filters.html#feComponentTransferElement
 * @property string type        = "identity | table | discrete | linear | gamma" Indicates the type of component
 *           transfer function. The type of function determines the applicability of the other attributes.
 * @property string tableValues = "(list of <number>s)" When type="table", the list of <number>s v0,v1,...vn, separated
 *           by white space and/or a comma, which define the lookup table. An empty list results in an identity
 *           transfer function. If the attribute is not specified, then the effect is as if an empty list were
 *           provided. In the following, C is the initial component (e.g., 'feFuncR'), C' is the remapped component;
 *           both in the closed interval [0,1].
 * @property float  slope       = "<number>" When type="linear", the slope of the linear function. If the attribute is
 *           not specified, then the effect is as if a value of 1 were specified.
 * @property float  intercept   = "<number>" When type="linear", the intercept of the linear function. If the attribute
 *           is not specified, then the effect is as if a value of 0 were specified.
 * @property float  amplitude   = "<number>" When type="gamma", the amplitude of the gamma function. If the attribute
 *           is not specified, then the effect is as if a value of 1 were specified.
 * @property float  exponent    = "<number>" When type="gamma", the exponent of the gamma function. If the attribute is
 *           not specified, then the effect is as if a value of 1 were specified.
 * @property float  offset      = "<number>" When type="gamma", the offset of the gamma function. If the attribute is
 *           not specified, then the effect is as if a value of 0 were specified.
 * @package nstdio\svg\filter
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class ComponentTransfer extends BaseFilter implements ContainerInterface
{
    use ElementTrait;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "feComponentTransfer";
    }

    public static function identity(ContainerInterface $container, $filterId = null)
    {
        return self::createAndAppend($container, [
            'id'   => $filterId,
            'type' => __FUNCTION__,
        ], true);

    }

    /**
     * @param ContainerInterface $container
     * @param                    $config
     *
     * @param bool               $alpha
     *
     * @return Filter
     */
    private static function createAndAppend(ContainerInterface $container, $config, $alpha = false)
    {
        $id = $config['id'];
        $type = $config['type'];
        unset($config['id'], $config['type']);

        $filter = self::defaultFilter($container, $id);
        $componentTransfer = new ComponentTransfer($container);
        $componentTransfer->id = null;

        $funcFactory = self::funcFactory($container, $type, $config, $alpha);
        $componentTransfer->append($funcFactory[0], $funcFactory[1], $funcFactory[2]);

        if (count($funcFactory) > 3) {
            $componentTransfer->append($funcFactory[3]);
        }

        $filter->append($componentTransfer);
        $container->append($filter);

        return $filter;
    }

    private static function funcFactory(ContainerInterface $container, $type, array $config, $alpha = false)
    {
        /** @var Func[] $ret */
        $ret = [new FuncR($container, $type), new FuncG($container, $type), new FuncB($container, $type)];

        if ($alpha === true) {
            $ret[] = new FuncA($container, $type);
        }
        foreach ($config as $key => $value) {
            KeyValueWriter::apply($ret[$key]->getElement(), $value);
        }

        return $ret;
    }

    public static function table(ContainerInterface $container, array $table, $filterId = null)
    {
        $table = self::padAttribute($table);
        $config = ['id' => $filterId, 'type' => __FUNCTION__];
        for ($i = 0; $i < 3; $i++) {
            $config[$i]['tableValues'] = implode(' ', $table[$i]);
        }

        return self::createAndAppend($container, $config);
    }

    private static function padAttribute(array $attributeValue, $max = 3)
    {
        $val = array_values(array_slice($attributeValue, 0, $max));
        $cnt = count($val);
        if ($cnt < $max) {
            $repeatValue = $val[$cnt - 1];
            for ($i = $cnt - 1; $i < $max; $i++) {
                $val[$i] = $repeatValue;
            }
        }

        return $val;
    }

    public static function linear(ContainerInterface $container, $slope, $intercept, $filterId = null)
    {
        $config = ['id' => $filterId, 'type' => __FUNCTION__];

        self::buildConfig($config, [
            'slope'     => $slope,
            'intercept' => $intercept,
        ]);

        return self::createAndAppend($container, $config);
    }

    private static function buildConfig(array &$config, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $aligned = self::padAttribute($value);
                for ($i = 0; $i < 3; $i++) {
                    $config[$i][$key] = $aligned[$i];
                }
            } else {
                for ($i = 0; $i < 3; $i++) {
                    $config[$i][$key] = $value;
                }
            }
        }
    }

    public static function gamma(ContainerInterface $container, $amplitude, $exponent, $offset = 0, $filterId = null)
    {
        $config = ['id' => $filterId, 'type' => __FUNCTION__];
        self::buildConfig($config, [
            'amplitude' => $amplitude,
            'exponent'  => $exponent,
            'offset'    => $offset,
        ]);

        return self::createAndAppend($container, $config);
    }

    public static function brightness(ContainerInterface $container, $amount, $filterId = null)
    {
        $config = ['id' => $filterId, 'type' => 'linear'];
        self::buildConfig($config, [
            'slope' => $amount,
        ]);

        return self::createAndAppend($container, $config);
    }

    public static function contrast(ContainerInterface $container, $amount, $filterId = null)
    {
        $config = ['id' => $filterId, 'type' => 'linear'];

        $intercept = 0.5 - (0.5 * $amount);
        self::buildConfig($config, [
            'slope'     => $amount,
            'intercept' => $intercept,
        ]);

        return self::createAndAppend($container, $config);
    }
}