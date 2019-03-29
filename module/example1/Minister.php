<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 17:06
 */

class Minister
{
    public function getEmperorSays()
    {
        $emperor = Emperor::getEmperor();
        $emperor->emperorSay();
    }
}