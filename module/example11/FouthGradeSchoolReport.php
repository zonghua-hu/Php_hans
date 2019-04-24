<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/22
 * Time: 11:12
 */

class FouthGradeSchoolReport extends SchoolReport
{


    public function report()
    {
        echo "我的成绩单~~~";
    }

    public function sign($father_pen)
    {
        echo "家长签名为：".$father_pen;
    }


}