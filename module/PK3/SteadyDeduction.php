<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:32
 */
class SteadyDeduction implements IDeduction
{

    public function exec($card, $trade)
    {
       $half_money = ($trade->getAmount())/2.0;
       $card->setFreeMoney($card->getFreeMoney() - $half_money);
       $card->setSteadyMoney($card->getSteadyMoney() - $half_money);
       return true;
    }

}