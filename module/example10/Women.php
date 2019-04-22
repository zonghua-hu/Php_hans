<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 12:06
 */

class Women implements IWomen
{
    private $type;

    private $request;

    public function __construct($type,$str_ques)
    {
        $this->type = $type;
        $this->request = $str_ques;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getResult()
    {
        return $this->request;
    }
}