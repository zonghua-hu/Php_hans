<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 16:44
 */

class FactoryOpera
{
    public static $operation = '';
    public static $opobj;
    /**
     * 工厂模式核心方法，返回相应的对象
     * @param $strings
     * @return OperaAdd|OperaDiv|OperaMul|OperaSub
     * @author Hans
     * @date 2019/3/25
     * @time 15:29
     */
    public static function createFactory($strings)
    {
        self::$operation = $strings;

        switch (self::$operation) {
            case '+':
                return self::$opobj = new OperaAdd();
            case '-':
                return self::$opobj = new OperaSub();
            case '*':
                return self::$opobj = new OperaMul();
            case '/':
                return self::$opobj = new OperaDiv();
        }
    }

}

