<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:34
 */

class StrategyFactory
{
    private static $deduction = null;

    public static function getDeduction(StrategyMan $man)
    {
        try
        {
            if ($man == StrategyMan::$free_deduction) {
                self::$deduction = new FreeDeduction();
            } elseif ($man == StrategyMan::$steady_deduction) {
                self::$deduction = new SteadyDeduction();
            }
        } catch (Exception $exception){
            //TODO
        }
        return self::$deduction;
    }

}