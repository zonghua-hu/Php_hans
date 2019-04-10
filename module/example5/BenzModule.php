<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 10:11
 */

class BenzModule extends CarModule
{
    public function start()
    {
        return "奔驰车启动";
    }

    public function stop()
    {
        return "奔驰车停止";
    }

    public function engineBoom()
    {
        return "奔驰车引擎轰鸣~";
    }

    public function alarm()
    {
        return "奔驰车喇叭响了";
    }

}