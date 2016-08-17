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

    public function __construct()
    {
        $this->rect = [
            'width'  => 0,
            'height' => 0,
            'x'      => null,
            'y'      => null,
        ];
    }

    public function addData($type, array $params)
    {
        $this->data[] = [$type => $params];
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

            switch ($this->modifier) {
                case 'L':
                    $this->getLBox();
                    break;
                case 'l':
                    $this->getLRelBox();
                    break;
                case 'H':
                    $this->getHBox();
                    break;
                case 'h':
                    $this->getHRelBox();
                    break;
                case 'V':
                    $this->getVBox();
                    break;
                case 'v':
                    $this->getVRelBox();
                    break;
                case 'Q':
                    $this->getQBox();
                    break;
                case 'C':
                    $this->getCBox();
                    break;
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

    private function getLRelBox()
    {
        list($x1, $y1) = $this->getStartPoint();
        list($x2, $y2) = $this->current;

        $x2 += $x1;
        $y2 += $y1;

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

    private function getHBox()
    {
        $x1 = $this->vhValue('x');
        $y1 = $this->vhValue('y');
        $x2 = $this->current[0];

        $this->union($x1, $y1, $x2, $y1);
    }


    private function getHRelBox()
    {
        $x1 = $this->vhValue('x');
        $y1 = $this->vhValue('y');
        $x2 = $this->current[0];

        $x2 += $x1;

        $this->union($x1, $y1, $x2, $y1);
    }

    private function getVBox()
    {
        $x1 = $this->vhValue('x');
        $y1 = $this->vhValue('y');
        $y2 = $this->current[0];

        $this->union($x1, $y1, $x1, $y2);
    }


    private function getVRelBox()
    {
        $x1 = $this->vhValue('x');
        $y1 = $this->vhValue('y');
        $y2 = $this->current[0];

        $y2 += $y1;

        $this->union($x1, $y1, $x1, $y2);
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

        if ($this->isRelativeModifier($this->index - 1)) {
            for ($i = $this->index - 2; $i >= 0; $i--) {
                $data = $this->data[$i][$this->modifierAtIndex($i)];
                if (!$this->isRelativeModifier($i)) {
                    $ret = $this->getStart($axis, $data);
                    $coordinate += $ret;
                    if ($axis === 'x' || $axis === 'y') {
                        $coordinate -= $this->getFirst($axis); // need for proper computation when relative modifier has negative value.
                        break;
                    }
                }
            }
        }

        return $coordinate;
    }

    private function getFirst($axis)
    {
        $mod = $this->modifierAtIndex(0);

        return $axis === 'x' ? $this->data[0][$mod][0] : $this->data[0][$mod][1];
    }

    private function getStart($axis, $data)
    {
        return $axis === 'x' ? $this->getStartX($data) : $this->getStartY($data);
    }

    /**
     * @param $index
     *
     * @return bool
     */
    private function isRelativeModifier($index)
    {
        return ctype_lower($this->modifierAtIndex($index));
    }

    /**
     * @param $axis
     *
     * @return mixed
     */
    private function vhValue($axis)
    {
        for ($i = $this->index - 1; $i >= 0; $i--) {
            $data = $this->data[$i];
            $modifier = key($data);
            $data = $data[$modifier];
            $modifier = strtolower($modifier);
            if ($modifier !== 'h' && $modifier !== 'v') {
                return $this->getStart($axis, $data);
            }
        }

        throw new \RuntimeException("Cannot found nearest {$axis} coordinate");
    }

    /**
     * @param $data
     *
     * @return float|false
     */
    private function getStartX($data)
    {
        reset($data);
        end($data);
        $x = prev($data);

        return $x;
    }

    /**
     * @param $data
     *
     * @return float
     */
    private function getStartY($data)
    {
        reset($data);
        $y = end($data);

        return $y;
    }
}
