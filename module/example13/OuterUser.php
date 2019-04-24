<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 10:34
 */

class OuterUser implements IOutUser
{
    public function getUserBaseInfo()
    {
        // TODO: Implement getUserBaseInfo() method.
        $user_info = [
            'userName' => '混世魔王',
            'mobileNumber'=>'02985848888',
        ];
        return $user_info;
    }

    public function getUserHomeInfo()
    {
        // TODO: Implement getUserHomeInfo() method.
        $home_info = [
            'homeAddress' => '魔都',
            'homeNumber'  =>'029-8848888',
        ];
        return $home_info;
    }

    public function getUserOfficeInfo()
    {
        // TODO: Implement getUserOfficeInfo() method.
        $job_info = [
            'jobPosition' => '魔帝',
            'officeNumber'=> '029-9999999',
        ];
        return $job_info;
    }

}