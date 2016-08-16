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
                case 'H':
                    $this->getHBox();
                    break;
                case 'V':
                    $this->getVBox();
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

        $this->union($this->getLineBox($x1, $y1, $x2, $y2));

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

    private function union($box)
    {
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

        $box = $this->getLineBox($x1, $y1, $x2, $y2);
        $this->union($box);
    }

    private function getCBox()
    {
        list($p0x, $p0y) = $this->getStartPoint();
        list($p1x, $p1y, $p2x, $p2y, $p3x, $p3y) = $this->current;

        list($x1, $y1, $x2, $y2) = Bezier::cubicBBox($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y);

        $box = $this->getLineBox($x1, $y1, $x2, $y2);
        $this->union($box);
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
        list($x1, $y1) = $this->getStartPoint();
        $x2 = $this->current[0];

        $box = $this->getLineBox($x1, $y1, $x2, $y1);

        $this->union($box);
    }

    private function getVBox()
    {
        list($x1, $y1) = $this->getStartPoint();
        $y2 = $this->current[0];

        $box = $this->getLineBox($x1, $y1, $x1, $y2);

        $this->union($box);
    }

    private function getLineBox($x1, $y1, $x2, $y2)
    {
        return [
            'width'  => max($x1, $x2) - min($x1, $x2),
            'height' => max($y1, $y2) - min($y1, $y2),
            'x'      => min($x1, $x2),
            'y'      => min($y1, $y2),
        ];
    }

    /**
     * @return array
     */
    private function getStartPoint()
    {
        $y1 = $this->getNearest('y');
        $x1 = $this->getNearest('x');
        // TODO: fix x1 and y1 null issue
        if (!$y1) {
            throw new \RuntimeException('y cannot be null');
        }
        if (!$x1) {
            throw new \RuntimeException('x cannot be null');
        }

        return [$x1, $y1];
    }

    private function getNearest($axis)
    {
        $prevData = $this->getPreviousData();
        if ($axis === 'x') {
            $coordinate = $this->getStartX($prevData);
            $restrictedModifier = 'h';
        } else {
            $coordinate = $this->getStartY($prevData);
            $restrictedModifier = 'v';
        }

        if ($coordinate === false) {
            for ($i = $this->index - 2; $i >= 0; $i--) {
                $data = $this->data[$i];
                $modifier = key($data);
                $data = $data[$modifier];
                if (strtolower($modifier) !== $restrictedModifier) { // path H modifier does not have a X coordinate.
                    return $axis === 'x' ? $data[count($data) - 2] : $data[count($data) - 1];
                }
            }
        }

        return $coordinate;
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
