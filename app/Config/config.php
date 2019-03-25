<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 15:11
 */

return array(
    'database' => array(
        'host'    => '192.168.85.135',
        'port'    => '3306',
        'user'    => 'weicheche',
        'pwd'     => 'weicheche',
        'charset' => 'utf8',
        'dbname'  => 'weicheche',
    ),
    'app'      => array(
        'default_platform'   => 'Admin',
        'default_controller' => 'ProductsModel.class',
        'default_action'     => 'list',
    )
);