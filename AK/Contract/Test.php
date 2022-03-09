<?php

use Contract\LikeType;
use Contract\PersonLikeDto;
use Contract\PersonNewDto;

$likes = [
    [
        'likeName'  => "游泳",
        'likeTitle' => LikeType::$typeMap[LikeType::LIKE_TYPE_SPORT],
        'likeType'  => LikeType::LIKE_TYPE_SPORT,
    ],
    [
        'likeName'  => "画画",
        'likeTitle' => LikeType::$typeMap[LikeType::LIKE_TYPE_PAINT],
        'likeType'  => LikeType::LIKE_TYPE_PAINT,
    ],
    [
        'likeName'  => "小猫",
        'likeType'  => LikeType::LIKE_TYPE_ANIMAL,
        'likeTitle' => LikeType::$typeMap[LikeType::LIKE_TYPE_ANIMAL],
    ]
];
$person = [
    'name' => "hans",
    "age" => 30,
    "sex" => true
];
$dto = new PersonNewDto($person);
foreach ($likes as $item) {
    $dto->likes[] = new PersonLikeDto($item);
}
var_dump($dto);