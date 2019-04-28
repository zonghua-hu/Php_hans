<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:31
 */

class LitterClient
{
    public function main()
    {
        $modern_post = new ModernPostOffice();
        $context = "hello, how are you~";
        $address = "深圳市宝安区南山小学";
        $modern_post->sendLetter($context,$address);
    }

}