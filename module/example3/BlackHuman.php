<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 15:56
 */

abstract class BlackHuman implements Human
{
    private $color;
    private $say;

    public function getColor()
    {
       return $this->color = '黑色';
    }

    public function talk()
    {
        return $this->say = 'I am human ~';
    }

}