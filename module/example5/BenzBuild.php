<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 10:10
 */

class BenzBuild extends CarBuild
{
    private $obj;

    public function __construct()
    {
        $this->obj = new BenzModule();
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