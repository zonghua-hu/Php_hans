<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/1
 * Time: 16:49
 */
namespace Swoft;
class App
{
    public static $server;

    public static function getAppProperties()
    {
        return self::$server;
    }
    public static function getAlias($value)
    {
        return isset($value)??(int)$value;
    }

}