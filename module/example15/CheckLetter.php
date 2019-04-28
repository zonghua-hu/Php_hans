<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:33
 */

class CheckLetter
{
    public function checkLetters(ILetterProcess $letter)
    {
       return $letter."已经检查过了";
    }

}