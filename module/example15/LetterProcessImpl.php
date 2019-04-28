<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:25
 */

class LetterProcessImpl implements ILetterProcess
{
    public function writeContext($context)
    {
        // TODO: Implement writeContext() method.
        echo "信件的内容是".$context;
    }
    
    public function fileEnvelope($address_info)
    {
        // TODO: Implement fileEnvelope() method.
        echo "收件人的地址是".$address_info;
    }

    public function letterInotoEnvelope()
    {
        // TODO: Implement letterInotoEnvelope() method.
        echo "把信件放到信封中去~~";
    }
    public function sendLetter()
    {
        // TODO: Implement sendLetter() method.
        echo "邮件快递信件~";
    }


}