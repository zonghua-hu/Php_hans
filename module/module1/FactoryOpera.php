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
     *策略模式方法
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
    /**
     * 工厂模式核心方法，返回相应的对象
     * @return OperaAdd
     * @author Hans
     * @date 2019/3/26
     * @time 14:10
     */
    public function createAdd()
    {
        return self::$opobj = new OperaAdd();
    }
    /**
     * 工厂模式核心方法，返回相应的对象
     * @return OperaSub
     * @author Hans
     * @date 2019/3/26
     * @time 14:11
     */
    public function createSub()
    {
        return self::$opobj = new OperaSub();
    }
    /**
     * 工厂模式核心方法，返回相应的对象
     * @return OperaMul
     * @author Hans
     * @date 2019/3/26
     * @time 14:11
     */
    public function createMul()
    {
        return self::$opobj = new OperaMul();
    }
    /**
     * 工厂模式核心方法，返回相应的对象
     * @return OperaDiv
     * @author Hans
     * @date 2019/3/26
     * @time 14:11
     */
    public function createDiv()
    {
        return self::$opobj = new OperaDiv();

    }




}

