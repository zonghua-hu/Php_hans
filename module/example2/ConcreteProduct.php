<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 11:14
 */

class ConcreteProduct extends Product
{
    private $pro_name;
    private $pro_sum;

    public function __construct($data)
    {
        $this->pro_name = $data['name'];
        $this->pro_sum = $data['sum'];
    }

    public function methodOne()
    {
        if ($this->pro_name == "") {
            return ['status' =>302,'data' =>'','info'=>'名字不能为空！'];
        }

        if (strlen($this->pro_name) < 6) {
            return ['status' =>301,'data' =>$this->pro_name,'info'=>'长度不符合！'];
        }

        if (!in_array($this->pro_name,['inter','microsoft'])) {
            return ['status' =>303,'data' =>$this->pro_name,'info'=>'名称不符合标准！'];
        }

        $this->methodThree($this->pro_name);

        return  ['status' =>200,'data' =>$this->pro_name,'info'=>'官方标准！'];

    }

    private function methodThree($string_name)
    {
        $this->pro_name = $string_name."00115";
    }

    public  function methodTwo()
    {
        if ($this->pro_sum == 0) {
            return ['status' =>304,'data' =>'','info'=>'个数不能为0！'];
        }
        $this->methodFour($this->pro_sum);
        return ['status' =>200,'data' =>$this->pro_sum,'info'=>'全新个数设置成功！'];
    }

    private function methodFour($number_cnt)
    {
        return $this->pro_sum = $number_cnt * $number_cnt + 1;
    }

}