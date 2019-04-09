<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9
 * Time: 11:11
 */

class HummerH1 extends HummerModel
{
    private $start;
    private $stop;
    private $boom;
    private $alarm;

    public function start()
    {
        return $this->start = "H1启动了";
    }

    public function stop()
    {
        return $this->stop = "H1停止了";
    }

    public function engineBoom()
    {
        return $this->boom = "H1引擎启动~";
    }

    public function alarm()
    {
        return $this->alarm = "H1喇叭刺耳~~";
    }

}