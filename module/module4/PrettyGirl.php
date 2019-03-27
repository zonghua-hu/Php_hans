<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 17:04
 */

class PrettyGirl implements IPrettyGirl
{

    private $name;

    public function __construct($str)
    {
        $this->name = $str;
    }
    /**
     * 实现接口的面容方法
     * @return mixed|string
     * @author Hans
     * @date 2019/3/27
     * @time 17:48
     */
    public function goodLooking()
    {
        return $this->name."我怎么这么好看~";
    }
    /**
     * 实现接口的身材方法
     * @return mixed|string
     * @author Hans
     * @date 2019/3/27
     * @time 17:48
     */
    public function niceFigure()
    {
        return $this->name."我身材怎么这么好~~";
    }
    /**
     * 实现接口的气质方法
     * @return mixed|string
     * @author Hans
     * @date 2019/3/27
     * @time 17:49
     */
    public function greatTemperament()
    {
        return $this->name."我怎么这么有气质~~~";
    }
}