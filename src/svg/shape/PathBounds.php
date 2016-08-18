<?php
namespace nstdio\svg\shape;

use nstdio\svg\util\Bezier;

/**
 * Class PathBounds
 *
 * @package nstdio\svg\shape
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class PathBounds
{
    private $index;

    private $modifier;
    /**
     * The path points positions
     *
     * @var array
     */
    private $data = [];

    private $current;

    private $rect;

    private $originalData;

    public function __construct()
    {
        $this->rect = [
            'width'  => 0,
            'height' => 0,
            'x'      => null,
            'y'      => null,
        ];
    }

    /**
     * @param string $modifier Path modifier e.g. V, L, C, M
     * @param array  $params
     */
    public function addData($modifier, array $params)
    {
        $this->originalData = [$modifier => $params];
        if ($this->isRelativeModifier($modifier)) {
            $prevData = $this->getLastData();

            if ($modifier === 'h') {
                $params[0] += $this->getStartX($prevData);
                $params[1] = $this->getStartY($prevData);
            } elseif ($modifier === 'v') {
                $y = $params[0];
                $params[0] = $this->getStartX($prevData);
                $params[1] = $y + $this->getStartY($prevData);
            } elseif ($modifier === 'l') {
                $params[0] += $this->getStartX($prevData);
                $params[1] += $this->getStartY($prevData);
            }

            $this->data[] = [$modifier => $params];
        } else {
            $prevData = $this->getLastData();
            if ($modifier === 'H') {
                $params[1] = $this->getStartY($prevData);
            } elseif ($modifier === 'V') {
                $y = $params[0];
                $params[0] = $this->getStartX($prevData);
                $params[1] = $y;
            }

            $this->data[] = [$modifier => $params];
        }
    }

    /**
     * @return array
     */
    public function getBox()
    {
        foreach ($this->data as $key => $value) {
            $this->modifier = key($value);
            $this->index = $key;
            $this->current = $value[$this->modifier];

            if ($this->isAnyKindOfLine()) {
                $this->getLBox();
            } elseif ($this->modifier === 'Q') {
                $this->getQBox();
            } elseif ($this->modifier === 'C') {
                $this->getCBox();
            }
        }
        unset($this->modifier, $this->index, $this->current);

        return $this->rect;
    }

    private function getLBox()
    {
        list($x1, $y1) = $this->getStartPoint();
        list($x2, $y2) = $this->current;

        $this->union($x1, $y1, $x2, $y2);
    }

    private function getPreviousData()
    {
        $mod = $this->modifierAtIndex($this->index - 1);

        return $this->data[$this->index - 1][$mod];
    }

    private function modifierAtIndex($index)
    {
        return key($this->data[$index]);
    }

    private function union($x1, $y1, $x2, $y2)
    {
        $box = [
            'width'  => max($x1, $x2) - min($x1, $x2),
            'height' => max($y1, $y2) - min($y1, $y2),
            'x'      => min($x1, $x2),
            'y'      => min($y1, $y2),
        ];

        if ($this->rect['x'] === null) {
            $this->rect['x'] = $box['x'];
            $this->rect['y'] = $box['y'];
        }
        $this->rect = Rect::union($this->rect, $box);
    }

    private function getQBox()
    {
        list($p0x, $p0y) = $this->getStartPoint();
        list($p1x, $p1y, $p2x, $p2y) = $this->current;

        list($x1, $y1, $x2, $y2) = Bezier::quadraticBBox($p0x, $p0y, $p1x, $p1y, $p2x, $p2y);

        $this->union($x1, $y1, $x2, $y2);
    }

    private function getCBox()
    {
        list($p0x, $p0y) = $this->getStartPoint();
        list($p1x, $p1y, $p2x, $p2y, $p3x, $p3y) = $this->current;

        list($x1, $y1, $x2, $y2) = Bezier::cubicBBox($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y);

        $this->union($x1, $y1, $x2, $y2);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    private function getStartPoint()
    {
        $x1 = $this->getNearest('x');
        $y1 = $this->getNearest('y');

        return [$x1, $y1];
    }

    private function getNearest($axis)
    {
        $prevData = $this->getPreviousData();
        $coordinate = $axis === 'x' ? $this->getStartX($prevData) : $this->getStartY($prevData);

        return $coordinate;
    }

    /**
     * @param string $mod
     *
     * @return bool
     */
    private function isRelativeModifier($mod)
    {
        return ctype_lower($mod);
    }

    /**
     * @param array $data
     *
     * @return float|false
     */
    private function getStartX($data)
    {
        return $data[count($data) - 2];
    }

    /**
     * @param array $data
     *
     * @return float
     */
    private function getStartY($data)
    {
        return $data[count($data) - 1];
    }

    /**
     * @return array
     */
    private function getLastData()
    {
        if (empty($this->data)) return [];
        $prevData = $this->data[count($this->data) - 1];
        $prevData = $prevData[key($prevData)];

        return $prevData;
    }

    private function isAnyKindOfLine()
    {
        $mod = strtolower($this->modifier);
        return $mod === 'l' || $mod === 'h' || $mod === 'v';
    }
}
