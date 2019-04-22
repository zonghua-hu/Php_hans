<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/18
 * Time: 17:05
 */

abstract class Handler
{
    const FATHER_QUES  = 1;
    const HUSBAND_QUES = 2;
    const SON_QUES     = 3;

    private $level = 0;

    private $hander;

    public function __construct($levels)
    {
        $this->level = $levels;
        $this->hander = $this;

    }

    final function handleMessage($obj)
    {
        echo $obj->getResult();
        echo "同意~";
    }

}