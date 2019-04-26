<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/26
 * Time: 14:51
 */

class LiSi implements ILiSi
{
    public function update($string_info)
    {
        // TODO: Implement update() method.
        echo "观察到韩非子活动了，开始向我报告~~";
        $this->reportHanFeiZi($string_info);
        echo "汇报完毕~~";
    }

    public function reportHanFeiZi($string_behave)
    {
        echo "李斯：报告，秦老板，韩非子有动作了~".$string_behave;
    }

}