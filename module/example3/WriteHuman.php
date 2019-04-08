<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 15:59
 */

abstract  class WriteHuman implements Human
{
    private $color;
    private $say;

    public function getColor()
    {
        return $this->color = "白色";
    }

    public function talk()
    {
        return $this->say = "I am Human~~~";
    }

}