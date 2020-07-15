<?php


namespace abstractFactory;


class BuyProduct implements Product
{
    protected $buyPrice;

    public function __construct($buyPrice)
    {
        $this->buyPrice = $buyPrice;
    }

    public function calculatePrice():float
    {
        return bcdiv($this->buyPrice,100,2);
    }

}