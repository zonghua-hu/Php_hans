<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 14:59
 */

class GirlAgain implements ITempGirl,GoodGirl
{
    private $name;

    public function __construct($string_name)
    {
        $this->name = $string_name;
    }

    public function goodBody()
    {
        return $this->name."身材真好看~";
    }

    public function lookNice()
    {
        return $this->name."脸蛋真好看~";
    }

    public function greatTemp()
    {
        return $this->name."主要看气质~~~";
    }

}