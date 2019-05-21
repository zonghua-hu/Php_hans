<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:34
 */

class StrategyMan
{
    public static $steady_deduction = "com.cbf4life.common.SteadyDeduction";

    public static $free_deduction = "com.cbf4life.common.FreeDeduction";

    private $str_value;

    public function __construct($strategy_man)
    {
        $this->str_value = $strategy_man;
    }

    public function getStrategyValue()
    {
        return $this->str_value;
    }

}