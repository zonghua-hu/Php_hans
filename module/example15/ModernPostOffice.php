<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:29
 */

class ModernPostOffice
{
    private $letter;
    private $check;

    public function __construct()
    {
        $this->letter = new LetterProcessImpl();
        $this->check = new CheckLetter();
    }

    public function sendLetter($context,$address)
    {
        $this->letter->writeContext($context);
        $this->letter->fileEnvelope($address);
        $this->check->checkLetters($this->letter);//添加扩展
        $this->letter->letterInotoEnvelope();
        $this->letter->sendLetter();
    }

}