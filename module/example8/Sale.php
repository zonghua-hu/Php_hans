<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 10:01
 */

class Sale
{

    private $stock;

    private $sale_number;

    private $purchase;

    public function __construct()
    {
        $this->stock = new Stock();

        $this->purchase = new PurChase();

    }

    public function getSaleStatus()
    {
        $this->sale_number = rand(1,100);
        echo "IBM电脑的销售情况是".$this->sale_number."台";
        return $this->sale_number;
    }

    public function sellIbmComputer($number)
    {
        if ($this->stock->getStockNumber() < $number) {
            $this->purchase->buyIbmComputer($number);
        }
        echo "销售IBM电脑".$number."台";
        $this->stock->delComputerNumber($number);
    }

    public function offSale()
    {
        echo "亏本大清仓，笔记本".$this->stock->getStockNumber()."台，全部八折~，记住是八折~";
    }

}