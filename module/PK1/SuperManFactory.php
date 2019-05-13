<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/13
 * Time: 10:07
 */

class SuperManFactory
{
    public static function createSuperMan($string_type)
    {
        $super_man = null;
        if ($string_type == "adult") {
            $super_man = new AdultSuperMan();
        } elseif ($string_type == "child") {
            $super_man = new ChildSuperMan();
        }
        return $super_man;
    }

}