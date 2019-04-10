<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 9:57
 */
abstract class CarModule
{
    private $list;

    protected abstract function start();

    protected abstract function stop();

    protected abstract function alarm();

    protected abstract function engineBoom();

    final function run()
    {
        foreach ($this->list as $item) {
            if ($item == "start") {
                $this->start();
            } elseif ($item == "stop") {
                $this->stop();
            }  elseif ($item == "alarm") {
                $this->alarm();
            } elseif ($item == "engineBoom") {
                $this->engineBoom();
            }
        }
    }

    final function setListRun($list)
    {
        $this->list = $list;
    }

}