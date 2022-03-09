<?php

namespace Contract;

class LikeType
{
    /**
     * 爱好类别-运动类
     */
    const LIKE_TYPE_SPORT = 1;
    /**
     * 爱好类别-绘画类
     */
    const LIKE_TYPE_PAINT = 2;
    /**
     * 爱好类别-动物类
     */
    const LIKE_TYPE_ANIMAL = 3;

    public static $typeMap = [
        self::LIKE_TYPE_SPORT  => '运动类',
        self::LIKE_TYPE_PAINT  => '绘画类',
        self::LIKE_TYPE_ANIMAL => '动物类',
    ];
}
