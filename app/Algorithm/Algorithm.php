<?php

namespace app\Algorithm;

class Algorithm
{
    /**
     * @Notes:冒泡排序
     * @param $ambitionArray
     * @return mixed
     * @User: Hans
     * @Date: 2020/5/26
     * @Time: 5:28 下午
     */
    public function bubbleSort($ambitionArray)
    {
        $arrNum = count($ambitionArray);
        for ($i = 0;$i < $arrNum - 1; ++$i) {
            $flag = false;
            for ($j = 0;$j < $arrNum - 1 - $i;++$j) {
                if ($ambitionArray[$j] > $ambitionArray[$j + 1]) {
                    $temp = $ambitionArray[$j];
                    $ambitionArray[$j] = $ambitionArray[$j + 1];
                    $ambitionArray[$j+1] = $temp;
                    $flag = true;
                }
            }
            if (!$flag) {
                break;
            }
        }
        return $ambitionArray;
    }

    /**
     * @Notes:插入排序
     * @param $insertArray
     * @return mixed
     * @User: Hans
     * @Date: 2020/5/26
     * @Time: 6:00 下午
     */
    public function insertSort($insertArray)
    {
        $arrayNum = count($insertArray);
        if ($arrayNum <= 1) {
            return $insertArray;
        }
        for ($i = 0;$i < $arrayNum-1;++$i) {
            $temp = $insertArray[$i];
            $j = $i-1;
            for (;$j >= 0; --$j) {
                if ($insertArray[$j] > $temp) {
                    $insertArray[$j + 1] = $insertArray[$j];
                } else {
                    break;
                }
            }
            $insertArray[$j + 1] = $temp;
        }
        return $insertArray;
    }

    /**
     * @Notes:选择排序
     * @param $selectArray
     * @return mixed
     * @User: Hans
     * @Date: 2020/5/26
     * @Time: 6:19 下午
     */
    public function selectSort($selectArray)
    {
        $num = count($selectArray);
        if ($num <= 1) {
            return $selectArray;
        }
        for ($i = 0;$i <= $num-1;$i++) {
            $min = $i;
            for ($j = $i;$j <= $num;$j++) {
                if ($selectArray[$j] < $selectArray[$min]) {
                    $min = $j;
                }
            }
            $temp = $selectArray[$min];
            $selectArray[$min] = $selectArray[$i];
            $selectArray[$i] = $temp;
        }
        return  $selectArray;
    }

    /**
     * @Notes:快速排序
     * @param $quickArray
     * @return array
     * @User: Hans
     * @Date: 2020/5/27
     * @Time: 11:42 上午
     */
    public function quickSort($quickArray)
    {
        $minArray = [];
        $maxArray = [];
        $arrayNum = count($quickArray);
        $temp = $quickArray[$arrayNum - 1];
        for ($i = 0;$i < $arrayNum;$i++) {
            if ($quickArray[$i] > $temp) {
                $maxArray[$i] = $quickArray[$i];
            } else {
                $minArray[$i] = $quickArray[$i];
            }
        }
        return array_merge($minArray,$maxArray);
    }

    /**
     * @Notes:二分法查找数据，时间复杂度O（logn）
     * @param $binArray
     * @param $searchNumber
     * @return bool|float|int
     * @User: Hans
     * @Date: 2020/6/3
     * @Time: 7:23 下午
     */
    public function binarySearch($binArray,$searchNumber)
    {
        $low = 0;
        $arrayLength = count($binArray);
        $high = $arrayLength-1;

        while  ($low <= $high) {
//            $mid = ($low + $high) / 2;
//            $mid = $low + ($high - $low) / 2;//防数据太大，溢出写法
            $mid = $low + (($high-$low) >> 1); //极致性能写法
            if ($binArray[$mid] == $searchNumber) {
                return $mid;
            } elseif ($binArray[$mid] < $searchNumber) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        return false;
    }

    /**
     * @Notes:极限二分法--递归写法
     * @param $arr
     * @param $low
     * @param $high
     * @param $searchNum
     * @return bool|int
     * @User: Hans
     * @Date: 2020/6/3
     * @Time: 7:36 下午
     */
    public function binSearchBack($arr, $low, $high, $searchNum)
    {
        if ($low > $high) return false;

        $mid = $low + (($high-$low) >> 1);
        if ($arr[$mid] == $searchNum) {
            return $mid;
        } elseif ($arr[$mid] < $searchNum) {
            return $this->binSearchBack($arr, $mid + 1, $high, $searchNum);
        } else {
            return $this->binSearchBack($arr, $low, $mid - 1, $searchNum);
        }
    }

    /**
     * @Notes:查找第一个值等于给定值的方法
     * @param $arr
     * @param $searchNumber
     * @return bool|int
     * @User: Hans
     * @Date: 2020/6/4
     * @Time: 8:23 下午
     */
    public function binarySearchFirst($arr,$searchNumber)
    {
        $arrLength = count($arr);
        $low = 0;
        $high = $arrLength - 1;

        while ($low <= $high) {
            $mid = $low + (($high - $low) >> 1);
            if ($arr[$mid] > $searchNumber) {
                $high = $mid - 1;
            } elseif ($arr[$mid] < $searchNumber) {
                $low = $mid + 1;
            } else {
                if ($mid == 0 || $arr[$mid - 1] != $searchNumber) {
                    return $mid;
                } else {
                    $high = $mid - 1;
                }
            }

        }
        return false;
    }

    /**
     * @Notes:查找最后一个值等于给定值的方法
     * @param $arr
     * @param $num
     * @return bool|int
     * @User: Hans
     * @Date: 2020/6/4
     * @Time: 8:24 下午
     */
    public function binarySearchLast($arr, $num)
    {
        $count = count($arr);
        $low = 0;
        $high = $count - 1;
        
        while ($low <= $high) {
            $mid = $low + (($high - $low) >>1);
            if ($arr[$mid] > $num) {
                $high = $mid - 1;
            } elseif ($arr[$mid] < $num) {
                $low = $mid + 1;
            } else {
                if ($mid == $count - 1 || $arr[$mid + 1] != $num) {
                    return $mid;
                } else {
                    return  false;
                }
            }
        }
        return false;
    }

    /**
     * @Notes:查找第一个大于等于给定值的方法
     * @param $arr
     * @param $num
     * @return bool|int
     * @User: Hans
     * @Date: 2020/6/4
     * @Time: 8:30 下午
     */
    public function binarySearchGreaterThan($arr, $num)
    {
        $count = count($arr);
        $low = 0;
        $high = $count - 1;
        
        while ($low <= $high) {
            $mid = $low +(($high - $low) >> 1);
            
            if ($arr[$mid] >= $num) {
                if ($mid == 0 || $arr[$mid - 1] < $num) {
                    return $mid;
                } else {
                    $high = $mid - 1;
                }
            } else {
                $low = $mid + 1;
            }
        }
        return  false;
    }

    /**
     * @Notes:查找最后一个小于等于给定值的方法
     * @param $arr
     * @param $num
     * @return bool|int
     * @User: Hans
     * @Date: 2020/6/4
     * @Time: 8:34 下午
     */
    public function binarySearchLessThan($arr,$num)
    {
        $count = count($arr);
        $low = 0;
        $high = $count - 1;

        while ($low <= $high) {
            $mid = $low +(($high - $low) >> 1);
            if ($arr[$mid] > $num) {
                $high = $mid - 1;
            } else {
                if ($mid == $count - 1 || $arr[$mid + 1] > $num) {
                    return $mid;
                } else {
                    $low = $mid + 1;
                }
            }
        }
        return false;
    }

    /**
     * @Notes:获取文件夹下面的所有文件
     * @param $path
     * @param $files
     * @User: Hans
     * @Date: 2020/8/28
     * @Time: 6:05 下午
     */
    public function getAllFiles($path, &$files)
    {
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp ->read()) {
                if ($file !== "." && $file !== "..") {
                    self::getAllFiles($path."/".$file, $files);
                }
            }
            $dp ->close();
        }
        if (is_file($path)) {
            $files[] =  $path;
        }
    }

    /**
     * @Notes:获取文件夹下面的所有文件
     * @param $dir
     * @return array
     * @User: Hans
     * @Date: 2020/8/28
     * @Time: 6:05 下午
     */
    public function getFileNamesByDir($dir)
    {
        $files =  array();
        $this->getAllFiles($dir, $files);
        return $files;
    }
}
