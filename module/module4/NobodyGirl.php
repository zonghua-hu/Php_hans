<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 17:13
 */

class NobodyGirl extends  AbstractSearch
{
    private $super_girl;

    public function __construct($obj)
    {
        if ($obj instanceof PrettyGirl) {
            $this->super_girl = $obj;
        }
    }
    /**
     * 实现抽象类的show方法
     * @author Hans
     * @date 2019/3/27
     * @time 17:51
     */
    public function show()
    {
        $this->super_girl->goodLooking();
        $this->super_girl->greatTemperament();
        $this->super_girl->niceFigure();
    }

}