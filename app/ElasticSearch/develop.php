<?php

/**
 * @desc 开发环境配置文件
 *
 * @author --
 * @copyright 2014-2018
 */

return [
    'application' => [
        'showErrors' => true,
        'baseUri' => '/',
    ],
    'database' => [
        'host' => 'dev-mysql.shylwlkj.com',
        'username' => 'root',
        'password' => 'cdb-97e8pp7v2019',
        'port' => '60853',
    ],
    'cache' => [
        'redis' => [
            "servers" => [
                [
                    'host' => '127.0.0.1',
                    'port' => 6379,
                    'auth' => '',
                ],
            ]
        ],
    ],
    'elastic' => [
        'host' => '127.0.0.1',
        'port' => '9200',
        'data' => [
            //商品es库
            'shop' => [
                'index' => 'shop',                    //商品库
                'type' => 'yy_goods',                //商品主表
                'attr' => 'yy_goods_attr',           //商品规格表
                'category' => 'yy_goods_category',       //商品目录表
                'record' => 'yy_goods_exchange_record',//积分兑换商品记录表
                'img' => 'yy_goods_img',            //商品图片表
                'invented' => 'yy_goods_invented',       //虚拟商品附表
                'physical' => 'yy_goods_physical',       //实体商品附表
                'rule' => 'yy_goods_rule',           //商品限制规则表
                'cart' => 'yy_shopping_cart'         //购物车记录表
            ],
            //服装es库
            'clothes' => [
                'index' => 'clothes',
                'type' => 'yy_clothes'
            ],
        ]
    ],
];
