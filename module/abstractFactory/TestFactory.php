<?php


namespace abstractFactory;


class TestFactory
{
    public function testCreateSellProduct()
    {
        $factory = new ProductFactory();
        $product = $factory->createSellProduct(100);
        return $product->calculatePrice();
    }

    public function testCreateBuyProduct()
    {
        $factory = new ProductFactory();
        $product = $factory->createBuyProduct(20);
        return $product->calculatePrice();
    }

}