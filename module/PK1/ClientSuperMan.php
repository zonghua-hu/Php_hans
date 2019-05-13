<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/13
 * Time: 10:15
 */

class ClientSuperMan
{
    public function main()
    {
        $super = SuperManFactory::createSuperMan("adult");
        $super->specialTalent();
    }

}