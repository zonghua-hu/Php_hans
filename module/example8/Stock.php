<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 10:01
 */

class Stock
{
    private $computer_number = 100;

    private $purchase;

    private $sale;

    public function __construct()
    {
        $this->purchase = new PurChase();

        $this->sale = new Sale();

    }

    public function getStockNumber()
    {
        return $this->computer_number;
    }

    public function increase($number)
    {
        return $this->computer_number+=$number;
    }

    public function delComputerNumber($del_number)
    {
        return $this->computer_number -=$del_number;
    }

    public function clearStock()
    {
        echo "当前存货数量为:".$this->computer_number.",希望采购暂时勿行动~";
        $this->sale->offSale();
        $this->purchase->refuseBuyIBM();
    }

}