<?php

require_once 'Lru.php';
require_once 'Algorithm.php';

$new = new app\Algorithm\Algorithm();
$time = time();
$water = 10;
for ($i = 0; $i < 100; $i++) {
    $res = $new->limitRate(50, 1, 5, $time, $water);
    var_dump($res);
}


//$obj = new \app\Algorithm\Lru(5);

//
//$obj->setValue(1, 1);
//$obj->setValue(2, 2);
//$obj->setValue(3, 3);
//$obj->setValue(4, 4);
//$obj->setValue(5, 5);
//$obj->setValue(6, 6);
//$obj->setValue(7, 7);
//$obj->setValue(8, 8);
//$obj->getValue(1);
//$obj->echoCache();
