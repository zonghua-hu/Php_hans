<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:09
 */

class FemalWriteHuman extends WriteHuman
{
    private $sex;

    public function getSex()
    {
        return $this->sex = "女";
    }

}