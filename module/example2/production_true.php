<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 11:52
 */

class production_true
{
    private $name;
    private $sum;

    public function productNow()
    {
        $this->name = isset($_GET['name'])?$_GET['name']:'英特尔主板';
        $this->sum  = isset($_GET['sum']) ?$_GET['sum']:'1000';

        $data = [
            'name' => $this->name,
            'sum'  => $this->sum,
        ];

        $res = ClientProduct::main($data);

        $pro_name = $res->methodOne();
        if ($pro_name['status'] != 200) {
            return $pro_name['info']."，请检查~~~";
        }

        $pro_sum  = $res->methodTwo();
        if ($pro_sum['status'] != 200) {
            return $pro_sum['info'].",请检查~~~";
        }

    }

}