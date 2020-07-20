<?php

namespace example1;

//class Emperor
//{
//    private static $emper;
//
//    public static function getEmperor()
//    {
//        if (!self::$emper instanceof Emperor) {
//            self::$emper = new self;
//        }
//        return self::$emper;
//    }
//
//    public function emperorSay()
//    {
//        return "众爱卿，平身，我是大唐皇帝李世民！";
//    }
//}
require __DIR__.'/Emperor.php';

//require "Emperor.php";

$emperor = Emperor::getEmperor();
$s = $emperor->emperorSay() . PHP_EOL;
echo $s;
