<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 17:37
 */
class Girls
{
    private $name = "陈嫣然";

    /**
     * 具体应用
     * @author Hans
     * @date 2019/3/27
     * @time 17:52
     */
    public function main()
    {
        $super_girl = new NobodyGirl(new PrettyGirl($this->name));

        $super_girl->show();
    }
}