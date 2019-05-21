<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:33
 */

class Trade
{
    private $trade_no = "";

    private $amount = 0;

    public function getTradeNo()
    {
        return $this->trade_no;
    }

    public function setTradeNo($trades)
    {
        $this->trade_no = $trades;
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function setAmount($amounts)
    {
        $this->amount = $amounts;
    }

}