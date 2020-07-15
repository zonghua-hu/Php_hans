<?php


namespace abstractFactory;

/**
 * sell product
 * Class SellProduct
 * @package abstractFactory
 */
class SellProduct implements Product
{

    protected $productPrice;

    protected $sellCost;

    public function __construct($productPrice, $sellCost)
    {
        $this->productPrice = $productPrice;
        $this->sellCost = $sellCost;
    }

    public function calculatePrice():float
    {
        return bcadd($this->sellCost, $this->productPrice,2);
    }

}
