<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:33
 */
class FreeDeduction implements IDeduction
{
    public function exec(Card $card, Trade $trade)
    {
        $card->setFreeMoney($card->getFreeMoney() - $trade->getAmount());
        return true;
    }

}