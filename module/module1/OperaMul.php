<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 16:44
 */

class OperaMul implements Operatation
{
    /**
     * 实现接口的getResult方法（重写）
     * @param $one
     * @param $two
     * @return float|int
     * @author Hans
     * @date 2019/3/25
     * @time 11:54
     */
    public function getResult($one,$two)
    {
        return $one * $two;
    }

}

