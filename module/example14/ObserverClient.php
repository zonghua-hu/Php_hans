<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/26
 * Time: 15:09
 */

class ObserverClient
{
    private $han;
    private $li;

    public function __construct()
    {
        $this->han = new HanFeiZi();

        $this->li = new LiSi();

    }

    public function main()
    {
        $watch = new Spy($this->han,$this->li,'breakfirst');
        $watch->run();

        $watch_fun = new Spy($this->han,$this->li,'havefun');
        $watch_fun->run();

        sleep(10);
        $this->han->haveBreakFirst();

        sleep(5);
        $this->han->haveFun();
    }

}