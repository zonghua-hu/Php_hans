<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/22
 * Time: 11:15
 */

class GradeReport extends FouthGradeSchoolReport
{
    private function reportHighScore()
    {
        return "我们班的最高成绩是80分~";
    }

    private  function reportHighSort()
    {
        return "我在我们班的排名是22名~";
    }

    public function report()
    {
        $this->reportHighScore();
        $this->report();
        $this->reportHighSort();
    }

}