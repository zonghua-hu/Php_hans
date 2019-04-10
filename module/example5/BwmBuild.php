<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 10:23
 */

class BwmBuild extends CarBuild
{
    private $obj;

    public function __construct()
    {
        $this->obj = new BwmModule();
    }

    public function getCarModule()
    {
        return $this->obj;
    }

    public function setList($data)
    {
        $this->obj->setListRun($data);
    }

}