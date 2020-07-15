<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:10
 */

class MaleWriteHuman extends WriteHuman
{
    private $sex;

    public function getSex()
    {
        return $this->sex = "男";
    }
}
