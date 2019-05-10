<?php
/**
 * 基于工厂模式的加减乘除四则运算
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 16:44
 */
class Opera
{
    private $one;
    private $two;

    /**
     * 构造函数
     * Opera constructor.
     */
    public function __construct()
    {
        self::formatData();
    }
    /**
     * 赋值方法
     * @author Hans
     * @date 2019/3/25
     * @time 15:24
     */
    public function formatData()
    {
        $this->one  = isset($_REQUEST['number_one'])?$_REQUEST['number_one']:1;
        $this->two  = isset($_REQUEST['number_two'])?$_REQUEST['number_two']:1;
    }
    /**
     * 主方法
     * @return float|int
     * @author Hans
     * @date 2019/3/25
     * @time 15:24
     */
    public function getResult()
    {
        $result = FactoryOpera::createFactory('+');         //返回对象
        $sum_result = $result->getResult($this->one,$this->two);   //对象调相应类下面的方法
        return $sum_result;
    }
}

