<?php
namespace nstdio\svg\util\transform;

/**
 * Class Packer
 *
 * @package nstdio\svg\util\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class Packer implements PackerInterface
{

    public function pack(array $data)
    {
        $count = count($data);
        if ($count === 1) {
            return $data;
        }

        for ($i = 0; $i < $count; $i++) {
            list($first, $transform) = $this->transformAndParams($data, $i);

            if ($i + 1 >= $count) {
                break;
            }

            list($second, $transform2) = $this->transformAndParams($data, $i + 1);

            if ($transform === $transform2) {
                $method = "sum" . ucfirst($transform);

                $data[$i][$transform] = $this->$method($first, $second);

                unset($data[$i + 1]);
                $data = array_values($data);
                $count--;
                $i--;
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @param       $idx
     *
     * @return array
     */
    private function transformAndParams(array $data, $idx)
    {
        $first = $data[$idx];
        $transform = array_keys($first)[0];
        $first = $first[$transform];

        return [$first, $transform];
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function toMatrix(array $data)
    {
        foreach ($data as $key => $transformParams) {
            list($params, $transformation) = $this->transformAndParams($data, $key);
            if ($transformation === 'matrix') { // we don't need to convert matrix to matrix
                continue;
            }

            $method = $transformation . "ToMatrix";
            $matrix = $this->$method($params);

            unset($data[$key][$transformation]);
            $data[$key]['matrix'] = $matrix;
        }

        return $data;
    }

    /**
     * @param array $scale
     *
     * @return array
     */
    private function scaleToMatrix(array $scale)
    {
        $sx = $scale[0];
        $sy = $scale[1] === null ? $sx : $scale[1];

        return [$sx, 0, 0, $sy, 0, 0];
    }

    private function rotateToMatrix(array $rotate)
    {
        $angle = deg2rad($rotate[0]);

        $cos = cos($angle);
        $sin = sin($angle);

        return [$cos, $sin, -$sin, $cos, 0, 0];
    }

    private function skewXToMatrix(array $skewX)
    {
        $angle = deg2rad($skewX[0]);

        return [1, 0, tan($angle), 1, 0, 0];
    }

    private function skewYToMatrix(array $skewY)
    {
        $angle = deg2rad($skewY[0]);

        return [1, tan($angle), 0, 1, 0, 0];
    }

    private function translateToMatrix(array $translate)
    {
        $tx = $translate[0];
        $ty = $translate[1] === null ? $tx : $translate[1];

        return [1, 0, 0, 1, $tx, $ty];
    }

    private function sumSkewX(array $first, array $second)
    {
        return ['skewX' => $this->sumSkew($first, $second)];
    }

    private function sumSkew(array $first, array $second)
    {
        return $first[0] + $second[0];
    }

    private function sumSkewY(array $first, array $second)
    {
        return ['skewY' => $this->sumSkew($first, $second)];
    }

    private function sumTranslate(array $first, array $second)
    {
        $y = null;
        if ($first[1] !== null || $second[1] !== null) {
            $y = $first[1] + $second[1];
        }

        return [
            $first[0] + $second[0],
            $y,
        ];
    }

    private function sumScale($first, $second)
    {
        $firstY = $this->scaleY($first);
        $secondY = $this->scaleY($second);

        $resultXScale = $first[0] * $second[0];
        $resultYScale = $firstY * $secondY;

        return [
            $resultXScale,
            $resultYScale,
        ];
    }

    /**
     * @param array $scaleParams
     *
     * @return mixed
     */
    private function scaleY(array $scaleParams)
    {
        return $scaleParams[1] === null ? $scaleParams[0] : $scaleParams[1];
    }

    private function sumRotate(array $first, array $second)
    {
        $sumRotate = $first[0] + $second[0];

        $rotateCx = null;
        if ($first[1] !== null || $second[1] !== null) {
            $rotateCx = ($first[1] + $second[1]) / 2;
        }

        $rotateCy = null;
        if ($first[2] !== null || $second[2] !== null) {
            $rotateCy = ($first[2] + $second[2]) / 2;
        }

        return [
            $sumRotate,
            $rotateCx,
            $rotateCy,
        ];
    }

    private function sumMatrix(array $first, array $second)
    {
        $first = $this->matrixFromVector($first);
        $second = $this->matrixFromVector($second);

        $result = [];
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $t = 0;
                for ($k = 0; $k < 3; $k++) {
                    $t += $first[$i][$k] * $second[$k][$j];
                }
                $result[$i][$j] = $t;
            }
        }

        return [
            $result[0][0], $result[1][0], $result[0][1],
            $result[1][1], $result[0][2], $result[1][2],
        ];
    }

    private function matrixFromVector(array $vector)
    {
        return [
            [$vector[0], $vector[2], $vector[4]],
            [$vector[1], $vector[3], $vector[5]],
            [0, 0, 1],
        ];
    }
}