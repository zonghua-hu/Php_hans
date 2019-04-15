<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 10:30
 */

class ComputerClient
{
    private $purchase;

    private $stock;
    
    private $sale;

    public function __construct()
    {
        $this->stock = new Stock();

        $this->sale = new Sale();

        $this->purchase = new PurChase();

    }

    public function main()
    {
        echo "采购人员开始买电脑";
        $this->purchase->buyIbmComputer(100);
        echo "销售人员卖电脑~";
        $this->sale->sellIbmComputer(20);
        echo "库存人员减轻库存";
        $this->stock->clearStock();
    }

}