<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 16:44
 */

class OperaAdd implements Operatation
{
    /**
     * 接口方法的实现
     * @param $one
     * @param $two
     * @return mixed
     * @author Hans
     * @date 2019/3/25
     * @time 15:27
     */
    public function getResult($one,$two)
    {
        return $one + $two;
    }

}

