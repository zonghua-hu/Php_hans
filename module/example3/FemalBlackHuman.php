<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:04
 */

class FemalBlackHuman extends BlackHuman
{
    private $sex;

    public function getSex()
    {
        return $this->sex = '女';
    }
}