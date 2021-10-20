<?php

require_once 'Lru.php';

$obj = new \app\Algorithm\Lru(5);

$obj->setValue(1, 1);
$obj->setValue(2, 2);
$obj->setValue(3, 3);
$obj->setValue(4, 4);
$obj->setValue(5, 5);
$obj->setValue(6, 6);
$obj->setValue(7, 7);
$obj->setValue(8, 8);
$obj->getValue(1);
$obj->echoCache();
