<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:35
 */

class DeductionFacade
{
    public static function deduct(Card $card,Trade $trade)
    {
        $strategy_man = self::getDeductionType($trade);
        $deduction = StrategyFactory::getDeduction($strategy_man);
        $ded_context = new DeductionContext($deduction);
        $ded_context->exec($card,$trade);
        return $card;
    }

    private  static function getDeductionType(Trade $trade)
    {
        if (strpos($trade->getTradeNo(),"abc")) {
            return StrategyMan::$steady_deduction;
        } else {
            return StrategyMan::$free_deduction;
        }
    }

}