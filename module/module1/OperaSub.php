<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 16:44
 */

class OperaSub implements Operatation
{
    /**
     * 接口方法的实现
     * @param $number_one
     * @param $number_two
     * @return mixed
     * @author Hans
     * @date 2019/3/25
     * @time 15:27
     */
    public function getResult($number_one,$number_two)
    {
        return $number_one - $number_two;
    }

}

