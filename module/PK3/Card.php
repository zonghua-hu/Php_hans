<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:33
 */

class Card
{
    private $card_no = "";

    private $steady_money = 0;

    private $free_money = 0;

    public function getCardNo()
    {
        return $this->card_no;
    }
    public function setCardNo($car_id)
    {
        $this->card_no = $car_id;
    }
    public function getSteadyMoney()
    {
        return $this->steady_money;
    }
    public function setSteadyMoney($money)
    {
        $this->steady_money = $money;
    }
    public function getFreeMoney()
    {
        return $this->free_money;
    }
    public function setFreeMoney($money)
    {
        $this->free_money = $money;
    }
}