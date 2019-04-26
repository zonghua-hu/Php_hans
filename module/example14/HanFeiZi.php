<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/26
 * Time: 14:50
 */

class HanFeiZi implements IHanFeiZi
{
    private $ishavebreakfirst=false;
    private $ishavefun=false;

    public function haveBreakFirst()
    {
        // TODO: Implement haveBreakFirst() method.
        echo "韩非子开始吃饭了~";
        $this->ishavebreakfirst = true;
    }
    public function haveFun()
    {
        // TODO: Implement haveFun() method.
        echo "韩非子开始娱乐了~";
        $this->ishavefun = true;
    }

    public function isHaveBrerakFirst()
    {
        return $this->ishavebreakfirst;
    }

    public function setIsBreakFirst($is_first)
    {
        $this->ishavebreakfirst = $is_first;
    }

    public function isHaveFun()
    {
        return $this->ishavefun;
    }

    public function setIsHaveFun($is_fun)
    {
        $this->ishavefun = $is_fun;
    }

}