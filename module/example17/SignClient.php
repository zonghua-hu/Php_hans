<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 11:32
 */

class SignClient
{
    public function main()
    {
        for ($i = 0;$i < 10; $i++) {
            $subject = "科目".$i;
            for ($j = 0;$j < 10; $j++) {
                $key = $subject."考试地点".$j;
                SignInfoFactory::getObjFactory($key);
            }
        }
    }

}