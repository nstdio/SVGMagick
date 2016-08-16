<?php
namespace nstdio\svg\util;

/**
 * Class Bezier
 *
 * @package nstdio\svg\util
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Bezier
{
    const EPSILON = 0.00001;

    public static function quadraticBBox($p0x, $p0y, $p1x, $p1y, $p2x, $p2y)
    {
        $devx = $p0x + $p2x - 2 * $p1x;
        $devy = $p0y + $p2y - 2 * $p1y;

        self::avoidDivisionByZero($devy);
        self::avoidDivisionByZero($devx);

        $tx = ($p0x - $p1x) / $devx;
        $ty = ($p0y - $p1y) / $devy;

        self::checkBezierInterval($tx);
        self::checkBezierInterval($ty);

        $txByTX = self::getQuadraticValue($tx, $p0x, $p1x, $p2x);
        $tyByTX = self::getQuadraticValue($tx, $p0y, $p1y, $p2y);

        $txByTY = self::getQuadraticValue($ty, $p0x, $p1x, $p2x);
        $tyByTY = self::getQuadraticValue($ty, $p0y, $p1y, $p2y);

        $x1 = min($txByTX, $p0x, $p2x, $txByTY);
        $y1 = min($tyByTX, $p0y, $p2y, $tyByTY);


        $x2 = max($txByTX, $p0x, $p2x, $txByTY);
        $y2 = max($tyByTX, $p0y, $p2y, $tyByTY);


        return [
            min($x2, $x1),
            min($y2, $y1),
            max($x2, $x1),
            max($y2, $y1),
        ];
    }

    public static function cubicBBox($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y)
    {
        $ax = $p3x - $p0x + 3 * ($p1x - $p2x);
        $bx = 2 * ($p0x - 2 * $p1x + $p2x);
        $cx = $p1x - $p0x;

        $ay = $p3y - $p0y + 3 * ($p1y - $p2y);
        $by = 2 * ($p0y - 2 * $p1y + $p2y);
        $cy = $p1y - $p0y;

        $txRoots = self::getRoots($ax, $bx, $cx);
        $tyRoots = self::getRoots($ay, $by, $cy);

        $tv0x = self::getCubicValue(0, $p0x, $p1x, $p2x, $p3x);
        $tv1x = self::getCubicValue($txRoots[0], $p0x, $p1x, $p2x, $p3x);
        $tv2x = self::getCubicValue($txRoots[1], $p0x, $p1x, $p2x, $p3x);
        $tv3x = self::getCubicValue(1, $p0x, $p1x, $p2x, $p3x);

        $tv0y = self::getCubicValue(0, $p0y, $p1y, $p2y, $p3y);
        $tv1y = self::getCubicValue($tyRoots[0], $p0y, $p1y, $p2y, $p3y);
        $tv2y = self::getCubicValue($tyRoots[1], $p0y, $p1y, $p2y, $p3y);
        $tv3y = self::getCubicValue(1, $p0y, $p1y, $p2y, $p3y);


        $x1 = min($tv0x, $tv1x, $tv2x, $tv3x, $p0x, $p3x);
        $y1 = min($tv0y, $tv1y, $tv2y, $tv3y, $p0y, $p3y);

        $x2 = max($tv0x, $tv1x, $tv2x, $tv3x, $p0x, $p3x);
        $y2 = max($tv0y, $tv1y, $tv2y, $tv3y, $p0y, $p3y);

        return [
            min($x2, $x1),
            min($y2, $y1),
            max($x2, $x1),
            max($y2, $y1),
        ];

    }

    public static function getRoots($a, $b, $c)
    {
        $dis = $b * $b - 4 * $a * $c;
        if ($dis < 0) {
            return null;
        }
        self::avoidDivisionByZero($a);

        $disSqrt = sqrt($dis);
        $root1 = (-$b + $disSqrt) / (2 * $a);
        $root2 = (-$b - $disSqrt) / (2 * $a);

        self::checkBezierInterval($root1);
        self::checkBezierInterval($root2);

        return [$root1, $root2];
    }

    private static function getCubicValue($t, $p0, $p1, $p2, $p3)
    {
        $omt = 1 - $t;
        $value = $p0 * $omt * $omt * $omt +
            3 * $p1 * $t * $omt * $omt +
            3 * $p2 * $t * $t * $omt +
            $p3 * $t * $t * $t;

        return $value;
    }

    /**
     * @param $value
     */
    private static function avoidDivisionByZero(&$value)
    {
        if ($value === 0) {
            $value = self::EPSILON;
        }
    }

    /**
     * @param $t
     */
    private static function checkBezierInterval(&$t)
    {
        if ($t > 1 || $t < 0) {
            $t = 0;
        }
    }

    /**
     * @param $t
     * @param $p0x
     * @param $p1x
     * @param $p2x
     *
     * @return mixed
     */
    private static function getQuadraticValue($t, $p0x, $p1x, $p2x)
    {
        $omt = 1 - $t;

        return $p0x * $omt * $omt + 2 * $t * $p1x * $omt + $p2x * $t * $t;
    }
}
