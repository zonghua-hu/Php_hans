<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 16:44
 */

class OperaDiv implements Operatation
{
    /**
     * 重写接口方法
     * @param $number_one
     * @param $number_two
     * @return float|int
     * @author Hans
     * @date 2019/3/25
     * @time 15:15
     */
    public function getResult($number_one,$number_two)
    {
        if ($number_two != 0) {
            return $number_one / $number_two;
        } else {
            return 0;
        }
    }
}

