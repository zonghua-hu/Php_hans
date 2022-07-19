<?php

namespace LeeCode;

class LeeCodeClass
{

    /**
     * leeCode:把两个数字相加。不得使用 + 或者其他算术运算符
     * @param int $numberOne
     * @param int $numberTwo
     * @return int
     */
    public function add(int $numberOne, int $numberTwo): int
    {
        if ($numberTwo == 0) {
            return $numberOne;
        } else {
            return $this->add($numberOne ^ $numberTwo, ($numberOne & $numberTwo) << 1);
        }
    }

    /**
     * @param string $str
     * @return bool
     */
    public function onlyOneChar(string $str): bool
    {
        $strArr = str_split($str);
        return count($strArr) == count(array_unique($strArr));
    }
}
