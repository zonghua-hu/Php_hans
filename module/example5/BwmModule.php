<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 10:14
 */

class BwmModule extends CarModule
{
    public function stop()
    {
        echo "宝马车停止~";
    }

    public function start()
    {
        echo "宝马车启动";
    }

    public function engineBoom()
    {
        echo "宝马车引擎轰鸣！";
    }

    public function alarm()
    {
        echo "宝马车鸣笛~";
    }

}