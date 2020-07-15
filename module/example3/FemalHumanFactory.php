<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:14
 */

class FemalHumanFactory implements HumanFactory
{
    public function createBlackHuman()
    {
        return new FemalBlackHuman();
    }

    public function createWriteHuman()
    {
        return new FemalWriteHuman();
    }

}
