<?php

declare(strict_types=1);

namespace app\common\utils;


class ArrayV2
{
    public function __construct()
    {
    }

    public function find($array, $key, $value)
    {
        try {
            $key2 = array_column($array, $key);
            $key3 = array_search($value, $key2);
            if ($key3 !== false) {
                return $array[$key3];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function compare($array, $array2)
    {
        try {
            $DIFF = [];
            foreach ($array as $k => $v) {
                $v2 = $array2[$k] ?? null;
                if (is_array($v)) {
                    $temp = $this->compare($v, $v2);
                    if (count($temp) > 0) {
                        $DIFF[$k] = $temp;
                    }
                } else if ($v !== $v2) {
                    $DIFF[$k] = $v;
                }
            }
            return $DIFF;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findIndex($array, $key, $value)
    {
        try {
            $key2 = array_column($array, $key);
            $key3 = array_search($value, $key2);
            return $key3;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete($array, $key, $value)
    {
        try {
            $key2 = array_column($array, $key);
            $key3 = array_search($value, $key2);
            if ($key3 !== false) {
                unset($array[$key3]);
                return $array;
            } else {
                return $array;
            }
        } catch (\Exception $e) {
            return $array;
        }
    }

    public function sort($array, $keys, $sort = 'desc')
    {
        try {
            $sort = $sort === 'desc' ? SORT_DESC : SORT_ASC;
            $keysValue = [];
            foreach ($array as $k => $v) {
                $keysValue[$k] = $v[$keys];
            }
            array_multisort($keysValue, $sort, $array);
            return $array;
        } catch (\Exception $e) {
            return $array;
        }
    }
}
