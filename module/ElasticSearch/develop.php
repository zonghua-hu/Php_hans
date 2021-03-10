<?php
/**
 * @desc 开发环境配置文件
 *
 * @author --
 * @copyright 2014-2018
 */

return [
    'application' => [
        'showErrors'     => true,
        'baseUri'        => '/',
    ],
    'database' => [
        'host'        => 'dev-mysql.shylwlkj.com',
        'username'    => 'root',
        'password'    => 'cdb-97e8pp7v2019',
        'port'        => '60853',
    ],
    'cache' => [
        'redis' => [
            "servers" => [
                [
                    'host'   => '127.0.0.1',
                    'port'   => 6379,
                    'auth' => '',
                ],
            ]
        ],
    ],
    'elastic' => [
        'host'        => '127.0.0.1',
        'port'        => '9200',
        'body' =>[
            'settings' => [
                'number_of_shards' => 3, //数据分片，不配置默认1，7以前默认5
                'number_of_replicas' => 0 //数据副本，因为只有一台，则配置为0，避免一直yellow，生产可以设置为机器的数量
            ],
            //分词器配置
            'mappings' => [
                "dynamic" => true,
                'properties' => [
                    'name' => [    //配置的字段
                        'type' => 'text',  //配置字段类型
                        'analyzer' => 'ik_max_word' //配置分词器
                    ],
                ]
            ]
        ],
        'data' => [
            //商品es库
            'goods'    => 'yy_goods',                //商品主表
            'attr'     => 'yy_goods_attr',           //商品规格表
            'category' => 'yy_goods_category',       //商品目录表
            'record'   => 'yy_goods_exchange_record',//积分兑换商品记录表
            'img'      => 'yy_goods_img',            //商品图片表
            'invented' => 'yy_goods_invented',       //虚拟商品附表
            'physical' => 'yy_goods_physical',       //实体商品附表
            'rule'     => 'yy_goods_rule',           //商品限制规则表
            'cart'     => 'yy_shopping_cart'         //购物车记录表
        ]
    ],
];
