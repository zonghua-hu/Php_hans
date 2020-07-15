<?php


namespace abstractFactory;


class ProductFactory
{
    const SELL_COSTS = 20;

    public function createSellProduct($sellPrice)
    {
        return new SellProduct($sellPrice, self::SELL_COSTS);
    }

    public function createBuyProduct($buyPrice)
    {
        return new BuyProduct($buyPrice);
    }
}
