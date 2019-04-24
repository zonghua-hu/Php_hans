<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/22
 * Time: 11:19
 */

class FatherClient
{
    public function main($string_nature)
    {
        $fathers = new GradeReport();

        $fathers->report();
        $fathers->sign($string_nature);

    }

}