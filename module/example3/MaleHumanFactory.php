<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 16:16
 */

class MaleHumanFactory implements HumanFactory
{
    public function createWriteHuman()
    {
        return new MaleWriteHuman();
    }

    public function createBlackHuman()
    {
        return new MaleBlackHuman();
    }

}