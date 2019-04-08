<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:08
 */

class MaleBlackHuman extends BlackHuman
{
    private $sex;

    public function getSex()
    {
        return $this->sex = "男";
    }

}