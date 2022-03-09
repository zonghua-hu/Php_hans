<?php

namespace tests\DtoTest;

use Contract\LikeType;
use Contract\PersonLikeDto;
use Contract\PersonNewDto;
use PHPUnit\Framework\TestCase;

class DtoTestOne extends TestCase
{
    public function testOne()
    {
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
        $this->assertEquals(3, count($dto->likes));
    }
}
