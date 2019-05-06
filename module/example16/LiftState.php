<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6
 * Time: 11:38
 */

abstract class LiftState
{
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public abstract function open();

    public abstract function close();

    public abstract function run();

    public abstract function stop();

}