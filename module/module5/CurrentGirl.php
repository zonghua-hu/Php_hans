<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 15:06
 */

class CurrentGirl
{
    private $name = "陈梦然";

    public function main()
    {
        $girl = new Searcher(new GirlAgain($this->name));
        $girl->show();
    }

}