<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:22
 */

interface ILetterProcess
{
    public function writeContext($string_con);

    public function fileEnvelope($address_info);

    public function letterInotoEnvelope();

    public function sendLetter();

}