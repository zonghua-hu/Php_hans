<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9
 * Time: 11:08
 */

abstract class HummerModel
{
    protected abstract function start();

    protected abstract function stop();

    protected abstract function alarm();

    protected abstract function engineBoom();

    final function run()
    {
        $this->start();
        $this->engineBoom();
        $this->alarm();
        $this->stop();
    }
}