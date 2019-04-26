<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/26
 * Time: 15:01
 */

class Spy extends Thread
{
    private $hanfeizi;
    private $lisi;
    private $rep_type;

    public function __construct(HanFeiZi $han,LiSi $li,$type)
    {
        $this->hanfeizi = $han;
        $this->lisi = $li;
        $this->rep_type = $type;
    }

    public function run()
    {
        while(true) {
            if ($this->rep_type == "breakfirst") {
                if ($this->hanfeizi->isHaveBrerakFirst()) {
                    $this->lisi->update("韩非子在吃早餐");
                    $this->hanfeizi->setIsBreakFirst(false);
                }
            } else {
                if ($this->hanfeizi->isHaveFun()) {
                    $this->lisi->update("韩非子在娱乐，下象棋！~");
                    $this->hanfeizi->setIsHaveFun(false);
                }
            }
        }
    }

}