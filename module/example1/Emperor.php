<?php

namespace example1;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 17:03
 */

class Emperor
{
    private static $emper;
    /**
     * 构造函数判断对象是否唯一
     * Emperor constructor.
     */
    public function __construct()
    {
        if (! self::$emper instanceof Emperor) {
            self::$emper = new self();
        }
    }
    /**
     * 禁止克隆
     * @author Hans
     * @date 2019/3/28
     * @time 17:08
     */
    public function __clone()
    {
    }

    /**
     * 获取实例化对象
     * @return Emperor
     * @author Hans
     * @date 2019/3/28
     * @time 17:09
     */
    public static function getEmperor()
    {
        return self::$emper;
    }
    /**
     * 实例方法
     * @author Hans
     * @date 2019/3/28
     * @time 17:09
     */
    public function emperorSay()
    {
        return  "众爱卿，平身，我是大唐皇帝李世民！";
    }
}
