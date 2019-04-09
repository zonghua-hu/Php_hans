<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9
 * Time: 11:08
 */

abstract class HummerModel
{
    public abstract function start();

    public abstract function stop();

    public abstract function alarm();

    public abstract function engineBoom();

    public function run()
    {
        $this->start();
        $this->engineBoom();
        $this->alarm();
        $this->stop();
    }
}