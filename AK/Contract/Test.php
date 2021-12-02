<?php

require_once 'BaseTrait.php';
require_once 'PersonDto.php';

$person = new \Contract\PersonDto();
$person->create([
    'sex' => true,
    'age' => 88
]);
print_r($person->toArray());die;
$person->setName('小明')->setLike("游泳")->setSex(true)->setAge(0);
print_r($person->toArray());die;


print_r($person);